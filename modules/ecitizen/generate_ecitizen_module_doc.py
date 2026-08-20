#!/usr/bin/env python3
"""
Generate a supervisor-ready Word document for the eCitizen module.

This script intentionally uses only the Python standard library so it can run on
servers where python-docx is not installed.
"""

from __future__ import annotations

import html
import zipfile
from datetime import datetime
from pathlib import Path


BASE_DIR = Path(__file__).resolve().parent
TECHNICAL_OUTPUT = BASE_DIR / "eCitizen_Module_Technical_Flow.docx"
PLAIN_LANGUAGE_OUTPUT = BASE_DIR / "eCitizen_Module_Plain_Language_Guide.docx"
PAGE_WIDTH = 10500


def esc(value: object) -> str:
    return html.escape(str(value), quote=True)


def text_run(text: str, bold: bool = False, italic: bool = False, color: str | None = None,
             size: int | None = None, font: str | None = None) -> str:
    props = []
    if bold:
        props.append("<w:b/>")
    if italic:
        props.append("<w:i/>")
    if color:
        props.append(f'<w:color w:val="{color}"/>')
    if size:
        props.append(f'<w:sz w:val="{size}"/>')
        props.append(f'<w:szCs w:val="{size}"/>')
    if font:
        props.append(
            f'<w:rFonts w:ascii="{esc(font)}" w:hAnsi="{esc(font)}" '
            f'w:cs="{esc(font)}"/>'
        )
    rpr = f"<w:rPr>{''.join(props)}</w:rPr>" if props else ""
    return f'<w:r>{rpr}<w:t xml:space="preserve">{esc(text)}</w:t></w:r>'


def paragraph(text: str = "", style: str | None = None, align: str | None = None,
              shading: str | None = None, color: str | None = None,
              bold: bool = False, italic: bool = False, size: int | None = None,
              font: str | None = None) -> str:
    ppr = []
    if style:
        ppr.append(f'<w:pStyle w:val="{style}"/>')
    if align:
        ppr.append(f'<w:jc w:val="{align}"/>')
    if shading:
        ppr.append(f'<w:shd w:fill="{shading}"/>')
    ppr_xml = f"<w:pPr>{''.join(ppr)}</w:pPr>" if ppr else ""
    return (
        f"<w:p>{ppr_xml}"
        f"{text_run(text, bold=bold, italic=italic, color=color, size=size, font=font)}"
        f"</w:p>"
    )


def spacer(size: int = 120) -> str:
    return f'<w:p><w:pPr><w:spacing w:after="{size}"/></w:pPr></w:p>'


def heading(text: str, level: int = 1) -> str:
    return paragraph(text, style=f"Heading{level}")


def bullet(text: str, level: int = 0) -> str:
    indent = 360 + (level * 360)
    return (
        "<w:p>"
        f"<w:pPr><w:ind w:left=\"{indent}\" w:hanging=\"240\"/></w:pPr>"
        f"{text_run('- ' + text)}"
        "</w:p>"
    )


def page_break() -> str:
    return '<w:p><w:r><w:br w:type="page"/></w:r></w:p>'


def table_props(width: int = PAGE_WIDTH, fixed: bool = True) -> str:
    layout = '<w:tblLayout w:type="fixed"/>' if fixed else ""
    return f'<w:tblPr><w:tblW w:w="{width}" w:type="dxa"/>{layout}</w:tblPr>'


def cell(content: str, shading: str | None = None, width: int | None = None,
         valign: str = "center") -> str:
    props = []
    if width:
        props.append(f'<w:tcW w:w="{width}" w:type="dxa"/>')
    if shading:
        props.append(f'<w:shd w:fill="{shading}"/>')
    if valign:
        props.append(f'<w:vAlign w:val="{valign}"/>')
    props.append(
        '<w:tcMar>'
        '<w:top w:w="150" w:type="dxa"/>'
        '<w:left w:w="150" w:type="dxa"/>'
        '<w:bottom w:w="150" w:type="dxa"/>'
        '<w:right w:w="150" w:type="dxa"/>'
        '</w:tcMar>'
    )
    return f"<w:tc><w:tcPr>{''.join(props)}</w:tcPr>{content}</w:tc>"


def table(headers: list[str], rows: list[list[str]], widths: list[int] | None = None,
          header_fill: str = "1F4E79") -> str:
    if widths:
        total = sum(widths)
        if total > 0 and total != PAGE_WIDTH:
            scaled = [max(700, int(width * PAGE_WIDTH / total)) for width in widths]
            scaled[-1] += PAGE_WIDTH - sum(scaled)
            widths = scaled
    border = (
        '<w:tblBorders>'
        '<w:top w:val="single" w:sz="6" w:color="D5DBE5"/>'
        '<w:left w:val="single" w:sz="6" w:color="D5DBE5"/>'
        '<w:bottom w:val="single" w:sz="6" w:color="D5DBE5"/>'
        '<w:right w:val="single" w:sz="6" w:color="D5DBE5"/>'
        '<w:insideH w:val="single" w:sz="6" w:color="D5DBE5"/>'
        '<w:insideV w:val="single" w:sz="6" w:color="D5DBE5"/>'
        '</w:tblBorders>'
    )
    out = [f'<w:tbl><w:tblPr><w:tblStyle w:val="TableGrid"/><w:tblW w:w="{PAGE_WIDTH}" w:type="dxa"/><w:tblLayout w:type="fixed"/>{border}</w:tblPr>']
    out.append("<w:tr>")
    for i, h in enumerate(headers):
        width = widths[i] if widths and i < len(widths) else None
        out.append(cell(paragraph(h, bold=True, color="FFFFFF"), header_fill, width))
    out.append("</w:tr>")
    for r_index, row in enumerate(rows):
        shade = "F8FAFC" if r_index % 2 == 0 else "FFFFFF"
        out.append("<w:tr>")
        for i, value in enumerate(row):
            width = widths[i] if widths and i < len(widths) else None
            paras = "".join(paragraph(line) for line in str(value).split("\n"))
            out.append(cell(paras, shade, width))
        out.append("</w:tr>")
    out.append("</w:tbl>")
    return "".join(out)


def callout(title: str, body: str, fill: str = "EAF3F8", accent: str = "1F4E79") -> str:
    content = paragraph(title, bold=True, color=accent, size=24) + "".join(
        paragraph(line, color="263238") for line in body.split("\n")
    )
    return (
        f'<w:tbl>{table_props()}'
        f'<w:tr>{cell(content, fill, PAGE_WIDTH)}</w:tr></w:tbl>'
    )


def code_block(text: str) -> str:
    content = "".join(
        paragraph(line, font="Consolas", size=18) for line in text.strip("\n").split("\n")
    )
    return (
        f'<w:tbl>{table_props()}'
        f'<w:tr>{cell(content, "F3F4F6", PAGE_WIDTH, "top")}</w:tr></w:tbl>'
    )


def cover_panel(title: str, subtitle: str, generated: str, label: str = "TECHNICAL BRIEF",
                audience: str = "Prepared for supervisory review") -> str:
    return (
        f'<w:tbl>{table_props()}'
        '<w:tr>'
        + cell(
            spacer(380)
            + paragraph(label, align="center", color="CFE8FF", bold=True, size=22)
            + paragraph(title, align="center", color="FFFFFF", bold=True, size=52)
            + paragraph(subtitle, align="center", color="DDEBF7", size=28)
            + spacer(160)
            + paragraph(audience, align="center", color="FFFFFF", bold=True, size=24)
            + paragraph(f"Generated: {generated}", align="center", color="DDEBF7", size=20)
            + paragraph("Module path: /var/www/html/smisportalndudev/modules/ecitizen", align="center", color="DDEBF7", size=20)
            + spacer(380),
            "17365D",
            PAGE_WIDTH,
        )
        + '</w:tr></w:tbl>'
    )


def snapshot_cards(cards: list[tuple[str, str, str]]) -> str:
    out = [f'<w:tbl>{table_props()}<w:tr>']
    width = int(PAGE_WIDTH / len(cards))
    for title, value, fill in cards:
        out.append(cell(
            paragraph(title.upper(), color="5B677A", bold=True, size=18)
            + paragraph(value, color="17365D", bold=True, size=25),
            fill,
            width,
        ))
    out.append("</w:tr></w:tbl>")
    return "".join(out)


def section_banner(number: str, title: str, subtitle: str) -> str:
    number_width = 1200
    title_width = PAGE_WIDTH - number_width
    return (
        f'<w:tbl>{table_props()}<w:tr>'
        + cell(paragraph(number, align="center", bold=True, color="FFFFFF", size=30), "1F4E79", number_width)
        + cell(
            paragraph(title, bold=True, color="1F4E79", size=30)
            + paragraph(subtitle, color="5B677A", size=20),
            "EAF3F8",
            title_width,
        )
        + "</w:tr></w:tbl>"
    )


def process_flow(title: str, steps: list[tuple[str, str, str]],
                 note: str = "Read left to right. Each block is a handoff point in the eCitizen flow.") -> str:
    rows = []
    for index, (label, detail, fill) in enumerate(steps, start=1):
        stage = label.strip()
        if stage.isdigit() or stage == "":
            explanation = detail
        else:
            explanation = f"{stage}: {detail}"
        rows.append(
            "<w:tr>"
            + cell(paragraph(f"{index}", align="center", bold=True, color="FFFFFF", size=24), fill, 900)
            + cell(paragraph(explanation, color="263238", size=21), "F8FAFC", PAGE_WIDTH - 900)
            + "</w:tr>"
        )

    border = (
        '<w:tblBorders>'
        '<w:top w:val="single" w:sz="6" w:color="D5DBE5"/>'
        '<w:left w:val="single" w:sz="6" w:color="D5DBE5"/>'
        '<w:bottom w:val="single" w:sz="6" w:color="D5DBE5"/>'
        '<w:right w:val="single" w:sz="6" w:color="D5DBE5"/>'
        '<w:insideH w:val="single" w:sz="6" w:color="FFFFFF"/>'
        '<w:insideV w:val="single" w:sz="6" w:color="FFFFFF"/>'
        '</w:tblBorders>'
    )
    flow_table = (
        '<w:tbl>'
        f'<w:tblPr><w:tblW w:w="{PAGE_WIDTH}" w:type="dxa"/><w:tblLayout w:type="fixed"/>{border}</w:tblPr>'
        '<w:tblGrid>'
        '<w:gridCol w:w="900"/>'
        f'<w:gridCol w:w="{PAGE_WIDTH - 900}"/>'
        '</w:tblGrid>'
        + "".join(rows)
        + '</w:tbl>'
    )
    return paragraph(title, bold=True, color="1F4E79", size=24) + flow_table


def status_grid() -> str:
    rows = [
        ["Pending", "Invoice exists, payment not confirmed", "Student can still pay/relaunch"],
        ["Credited", "Gateway confirmed and portal fee statement was credited", "SMIS posting is waiting for cron"],
        ["Settled", "SMIS banking slip, fee transaction, payment, and receipt completed", "Normal final state"],
        ["review_required", "Reconciliation found matched reversal/refund response", "Manual finance review required"],
    ]
    return table(["State", "Meaning", "Supervisor signal"], rows, [2200, 4600, 2800], "305496")


def document_body() -> str:
    generated = datetime.now().strftime("%Y-%m-%d %H:%M")
    body: list[str] = []

    body.append(cover_panel(
        "eCitizen Student Fee Payment Module",
        "Workflow, PostgreSQL interactions, cron jobs, controls, and operating notes",
        generated,
    ))
    body.append(spacer(240))
    body.append(snapshot_cards([
        ("Primary flow", "Portal credit first; SMIS posting by cron", "EAF3F8"),
        ("Payment mode", "SMIS fss_payment_modes ID 12", "E2F0D9"),
        ("Main queue", "smisportal.ecitizen", "FFF2CC"),
    ]))
    body.append(spacer(180))
    body.append(callout(
        "Supervisor summary",
        "The eCitizen module lets a logged-in student initiate a fee payment, sends the student to the eCitizen checkout iframe, credits the portal fee statement after a signed callback, and uses cron jobs to complete SMIS posting and audit settled payments.",
        "DDEBF7",
    ))
    body.append(spacer(120))
    body.append(callout(
        "Black-box explanation for non-technical readers",
        "Think of the module as a payment bridge. A student starts payment in the portal, pays through eCitizen, and the portal waits for eCitizen to confirm the payment. Once confirmed, the student can see the credit quickly. A background worker then finishes the official finance posting in SMIS. Another background check later confirms that payments marked complete locally still appear complete at eCitizen.",
        "E2F0D9",
        "548235",
    ))
    body.append(page_break())

    body.append(section_banner("00", "Reading Guide", "The document is arranged from business flow to database-level detail."))
    body.append(spacer(120))
    body.append(table(
        ["Section", "What it answers"],
        [
            ["Executive summary", "What the module does and where money records move."],
            ["Component map", "Which files own each responsibility."],
            ["Architecture and workflows", "How student checkout, callbacks, invoices, sync, and reconciliation work."],
            ["Database interactions", "Which PostgreSQL tables are read or written."],
            ["Configuration and security", "Which controls protect credentials, callbacks, hosts, tokens, and duplicate posting."],
            ["Operational checks", "What to run when checking cron, logs, and review-required payments."],
        ],
        [2800, 6600],
        "17365D",
    ))
    body.append(page_break())

    body.append(section_banner("01", "Executive Summary", "The main story in one page before the implementation details."))
    body.append(spacer(120))
    body.append(table(
        ["Plain question", "Plain answer"],
        [
            ["What does this module do?", "It helps students pay fees through eCitizen from inside the portal."],
            ["What happens after payment?", "The portal receives confirmation from eCitizen and credits the student fee statement."],
            ["Why is cron involved?", "Some official SMIS finance records are completed safely in the background instead of during the student's browser session."],
            ["What does reconciliation do?", "It checks completed payments again with eCitizen and flags any confirmed reversal or refund for review."],
            ["Does it reverse money automatically?", "No. Reversals are flagged for manual review so finance records are not changed without an approved process."],
        ],
        [2800, 6600],
        "548235",
    ))
    body.append(spacer(160))
    body.append(paragraph(
        "The module implements student fee payment through eCitizen inside the Yii-based SMIS Portal. "
        "It is intentionally split into a web-facing flow and background finance posting."
    ))
    for item in [
        "Students create or relaunch payment requests from the portal.",
        "The portal creates a pending row in smisportal.ecitizen and builds a signed eCitizen iframe payload.",
        "eCitizen sends a signed server-to-server notification when payment is complete.",
        "The notification immediately credits the student-facing portal fee statement.",
        "A one-minute cron job posts the matching records into the main SMIS finance tables.",
        "A separate reconciliation cron checks locally settled payments against eCitizen and flags reversals for review.",
    ]:
        body.append(bullet(item))
    body.append(callout(
        "Important accounting boundary",
        "The reconciliation job does not reverse fee balances, cancel receipts, or alter settled SMIS postings. Explicit reversal/refund responses are marked review_required for investigation.",
        "FCE4D6",
        "C00000",
    ))
    body.append(spacer(120))
    body.append(status_grid())

    body.append(page_break())
    body.append(section_banner("02", "Component Map", "Where the eCitizen responsibilities live in the codebase."))
    body.append(spacer(120))
    body.append(table(
        ["File or area", "Responsibility"],
        [
            ["modules/ecitizen/Module.php", "Defines the Yii module, controller namespace, portal DB component, and module-local SMIS DB connection from .env."],
            ["modules/ecitizen/config/module.php", "Loads eCitizen credentials and operational parameters such as gateway URL, callback base URL, allowed hosts, token TTL, maximum amount, and bank account."],
            ["modules/ecitizen/config/credentials.php", "Local secret-bearing configuration file. The document intentionally does not print secret values."],
            ["modules/ecitizen/models/forms/PaymentForm.php", "Validates amount, payment type, settlement account, narration, and phone number."],
            ["modules/ecitizen/controllers/PaymentController.php", "Handles web pages, checkout, signed notifications, invoices, payment completion fallback, and workflow report."],
            ["modules/ecitizen/services/PaymentService.php", "Contains payment business logic, gateway signing, notification validation, DB reads/writes, posting, and reconciliation logic."],
            ["modules/ecitizen/views/payment/*", "Student payment form, iframe checkout, invoices grid, success redirect, and workflow report view."],
            ["commands/EcitizenSyncController.php", "Console controller used by cron for SMIS posting and settled-payment reconciliation."],
            ["migrations/m260518_000001_create_ecitizen_payment_tables.php", "Creates eCitizen tables and write-guard triggers in portal and SMIS schemas."],
            ["migrations/m260602_000001_add_ecitizen_sync_columns.php", "Adds paid amount/date/reference and sync status columns."],
            ["migrations/m260704_000001_add_ecitizen_reconciliation_columns.php", "Adds remote status and reconciliation audit columns."],
            ["modules/ecitizen/NDU SERVICE CODES.xlsx", "Workbook used as the service-code catalog; smisportal.fss_payment_types rows are matched by description against it (read-only, never seeded/written)."],
        ],
        [3200, 6200],
    ))

    body.append(page_break())
    body.append(section_banner("03", "Architecture", "The runtime handoffs between the student, portal, gateway, databases, and cron."))
    body.append(spacer(120))
    body.append(process_flow("High-level flow", [
        ("Student", "Starts or relaunches payment", "305496"),
        ("Yii module", "Validates and signs request", "2F75B5"),
        ("eCitizen", "Hosts checkout and sends callback", "548235"),
        ("Portal DB", "Credits student-facing statement", "9E480E"),
        ("SMIS cron", "Posts finance records", "7030A0"),
    ]))
    body.append(spacer(160))
    body.append(table(
        ["Layer", "Primary responsibility"],
        [
            ["Web module", "Creates payment requests, renders checkout, receives notifications, shows invoices and reports."],
            ["Portal PostgreSQL schema", "Stores eCitizen queue rows and immediate student-facing credit records."],
            ["SMIS PostgreSQL schema", "Stores official finance posting: banking slip, fee transaction, fee payment, and receipt number."],
            ["Cron worker", "Moves confirmed portal rows into official SMIS posting and performs settled-payment audits."],
        ],
        [2500, 6900],
        "305496",
    ))

    body.append(page_break())
    body.append(section_banner("04", "Student Payment Workflow", "How a payment request is created and handed to eCitizen."))
    body.append(spacer(120))
    body.append(process_flow("Checkout creation", [
        ("Open form", "actionIndex", "305496"),
        ("Validate", "PaymentForm", "2F75B5"),
        ("Create row", "smisportal.ecitizen", "9E480E"),
        ("Sign payload", "secureHash", "548235"),
        ("Checkout", "eCitizen iframe", "7030A0"),
    ]))
    body.append(spacer(160))
    body.append(table(
        ["Step", "Code path", "What happens"],
        [
            ["1", "PaymentController::actionIndex", "Resolves the logged-in student and prepares the payment form, payment types, bank accounts, and recent requests."],
            ["2", "PaymentService::resolveLoggedInStudent", "Maps Yii identity adm_refno to a registration number, then reads SMIS student, programme curriculum, and latest academic progress."],
            ["3", "PaymentForm::rules", "Requires amount, payment type, narration, phone number, and bank account when one is not configured globally. Amount is positive and capped by maxPaymentAmount."],
            ["4", "PaymentController::actionCheckout", "Applies rate limit, validates payment mode 12, bank account, payment type, optional outstanding balance, and gateway configuration."],
            ["5", "PaymentService::createPendingBankingSlip", "Builds reference like ECIT-REGNO-YYYYMMDDHHMMSS-NNN and inserts a Pending invoice into smisportal.ecitizen within a portal DB transaction."],
            ["6", "PaymentService::buildGatewayPayload", "Builds the eCitizen iframe payload and secureHash using apiClientID, amount, serviceID, client details, bill reference, description, secret, and API key."],
            ["7", "views/payment/_checkout_iframe.php", "Posts the signed payload to the configured eCitizen gateway URL inside an iframe."],
        ],
        [900, 3000, 5500],
    ))

    body.append(page_break())
    body.append(section_banner("05", "Notification And Posting Workflow", "How confirmed payment becomes portal credit, then official SMIS posting."))
    body.append(spacer(120))
    body.append(process_flow("Confirmation to settlement", [
        ("Signed callback", "/notify", "305496"),
        ("Verify hash", "HMAC check", "2F75B5"),
        ("Queue sync", "Credited, status 0", "9E480E"),
        ("Portal credit", "fee statement CR", "548235"),
        ("SMIS sync", "Settled, status 1", "7030A0"),
    ]))
    body.append(spacer(160))
    body.append(table(
        ["Status", "Meaning"],
        [
            ["Pending", "Invoice created; payment not yet confirmed."],
            ["Paid", "Older or external paid state still eligible for sync."],
            ["Credited", "Signed callback or status fallback confirmed payment and portal fee statement was credited; SMIS posting is pending."],
            ["Settled", "SMIS posting completed and synced_trans_id points to the SMIS banking slip."],
            ["sync_status = 0", "Pending background synchronization."],
            ["sync_status = 1", "Synchronization complete."],
            ["sync_status = 2", "Synchronization failed; sync_error stores the reason."],
        ],
        [2200, 7200],
    ))

    body.append(page_break())
    body.append(section_banner("06", "Invoice Workflow", "How existing requests are listed, protected, and relaunched."))
    body.append(spacer(120))
    for item in [
        "actionInvoices lists both SMIS banking-slip invoices and still-pending portal eCitizen rows.",
        "PaymentService::invoiceToken encrypts trans_id with a dedicated invoiceTokenKey and binds it to the registration number.",
        "transIdFromInvoiceToken rejects malformed, expired, or wrong-student tokens. Default TTL is 900 seconds unless configured otherwise.",
        "actionInvoice relaunches an unpaid invoice into checkout if it is not already credited, posted, or settled.",
        "actionCompletePayment remains as a server-side fallback: it queries eCitizen status, verifies settled status, amount, and reference, then queues the same credit/sync flow.",
    ]:
        body.append(bullet(item))

    body.append(page_break())
    body.append(section_banner("07", "Console And Crontab Jobs", "The background processes that keep portal and SMIS finance records aligned."))
    body.append(spacer(120))
    body.append(snapshot_cards([
        ("Posting cron", "Every 1 minute", "EAF3F8"),
        ("Reconciliation cron", "Every 15 minutes", "E2F0D9"),
        ("Per-row status check", "Minimum 60 minutes", "FFF2CC"),
    ]))
    body.append(spacer(160))
    body.append(table(
        ["Cron entry", "Frequency", "Purpose"],
        [
            ["cd /var/www/html/smisportalndudev && /usr/bin/php yii ecitizen-sync/sync >> runtime/logs/ecitizen-sync.log 2>&1", "Every 1 minute", "Completes SMIS-side posting for portal-confirmed eCitizen payments."],
            ["cd /var/www/html/smisportalndudev && /usr/bin/php yii ecitizen-sync/reconcile-settled 100 60 >> runtime/logs/ecitizen-reconciliation.log 2>&1", "Every 15 minutes", "Checks settled rows against the eCitizen status API. Batch limit is 100; each row is normally rechecked no more than once per 60 minutes."],
        ],
        [5200, 1500, 3700],
    ))
    body.append(heading("ecitizen-sync/sync", 2))
    for item in [
        "Reads pending paid requests from smisportal.ecitizen where status is Paid or Credited and sync_status = 0.",
        "For each row, uses paid_amount or amountExpected, payment_date or trans_date, and gateway_reference or billRefNumber.",
        "Calls PaymentService::postPaidBankingSlip.",
        "On success, writes or updates SMIS fss_banking_slips, fss_fee_transactions, fss_fee_payments, fss_receipt_counter, then marks the portal row Settled.",
        "On failure, marks sync_status = 2 and records sync_error.",
    ]:
        body.append(bullet(item))
    body.append(heading("ecitizen-sync/reconcile-settled", 2))
    for item in [
        "Reads Settled rows due for checking from smisportal.ecitizen.",
        "Calls the configured eCitizen payment status endpoint.",
        "Confirms reference and amount before classifying the remote response.",
        "Sets reconciliation_status to confirmed, review_required, or unknown.",
        "Records remote_status, last_status_checked_at, status_check_error, last_status_response, and reversal_detected_at where relevant.",
    ]:
        body.append(bullet(item))

    body.append(page_break())
    body.append(section_banner("08", "Database Interactions", "The PostgreSQL tables touched by the workflow."))
    body.append(spacer(120))
    body.append(heading("Portal Database", 2))
    body.append(table(
        ["Table", "Read/Write", "Usage"],
        [
            ["smisportal.ecitizen", "Read/write", "Primary eCitizen invoice queue, status, sync, gateway metadata, and reconciliation audit table."],
            ["smisportal.sm_admitted_student", "Read", "Workflow report and identity context."],
            ["smisportal.sm_student_programme_curriculum", "Read", "Portal fee-statement posting context."],
            ["smisportal.sm_academic_progress", "Read", "Academic progress attached to portal fee credit."],
            ["smisportal.sm_student_sem_session_progress", "Read", "Optional student semester session ID for fee transaction."],
            ["smisportal.org_academic_session", "Read", "Builds progress_code from registration number and academic session name."],
            ["smisportal.fss_fee_transactions", "Read/write", "Student-facing portal fee statement CR entry created immediately after confirmed payment."],
            ["smisportal.fss_fee_payments", "Read/write", "Portal payment record tied to the portal fee transaction when collection point is available."],
            ["smisportal.fss_payment_types", "Read", "Payment type dropdown; rows matched by description against the NDU service codes workbook (never seeded/written)."],
        ],
        [3100, 1600, 4700],
    ))
    body.append(heading("SMIS Database", 2))
    body.append(table(
        ["Table", "Read/Write", "Usage"],
        [
            ["smis.sm_student", "Read", "Student master data by registration number."],
            ["smis.sm_student_programme_curriculum", "Read", "Programme curriculum context for posting."],
            ["smis.sm_academic_progress", "Read", "Academic progress attached to SMIS fee transaction."],
            ["smis.sm_student_sem_session_progress", "Read", "Optional student semester session ID for SMIS fee transaction."],
            ["smis.org_academic_session", "Read", "Builds SMIS progress_code."],
            ["smis.fss_payment_modes", "Read", "Validates eCitizen payment mode 12."],
            ["smis.fss_bank_accounts / fss_bank_branches / fss_banks", "Read", "Settlement account and collection point metadata."],
            ["smis.fss_banking_slips", "Read/write", "SMIS banking slip created or updated by sync job; marked POSTED with receipt and gateway reference."],
            ["smis.fss_fee_transactions", "Read/write", "SMIS fee ledger CR entry tied to the banking slip trans_id."],
            ["smis.fss_fee_payments", "Read/write", "SMIS fee payment row with receipt_no, amount, mode 12, collection point, and programme curriculum."],
            ["smis.fss_receipt_counter", "Read/write", "Allocates the next numeric receipt number if the slip has no receipt."],
        ],
        [3300, 1500, 4600],
    ))

    body.append(heading("Write Safety And Idempotency", 2))
    for item in [
        "Direct writes to ecitizen tables are guarded by a trigger that requires the session setting smisportal.ecitizen_app_write = '1'.",
        "Posting is protected per reference with pg_advisory_xact_lock(hashtext('ecitizen-payment-' || reference)).",
        "The sync job checks existing banking slips, fee transactions, and fee payments before creating new records.",
        "The portal fee credit stores portal_fee_trans_id in response metadata so repeated callbacks do not duplicate credits.",
        "The web context is blocked from posting directly into SMIS; SMIS posting is restricted to console execution.",
    ]:
        body.append(bullet(item))

    body.append(page_break())
    body.append(section_banner("09", "Configuration And Security", "The controls around credentials, hosts, callbacks, duplicate posting, and web safety."))
    body.append(spacer(120))
    body.append(table(
        ["Area", "Details"],
        [
            [".env", "Module-local SMIS connection: SMIS_DB_SERVER, SMIS_DB_PORT, SMIS_DB_NAME, SMIS_DB_USER, SMIS_DB_PASS."],
            ["credentials.php", "eCitizen API client ID, API key, secret, invoice token key, URLs, hosts, amount rules, currency, bank account, and STK option."],
            ["allowedPortalHosts", "Controls Yii HostControl and validates callbackBaseUrl."],
            ["allowedGatewayHosts", "Restricts gateway and status URLs to trusted HTTPS hosts."],
            ["Security headers", "No-store cache headers, DENY frame ancestors for portal pages, nosniff, referrer policy, permissions policy, CSP, and HSTS on HTTPS."],
            ["Rate limits", "Checkout 5/5 minutes, invoice launch 30/5 minutes, complete-payment 10/5 minutes, notification 300/minute."],
            ["Notification hash", "Expected hash is HMAC-SHA256 over reference, invoice number, amount, payment date, and secret using the API key."],
            ["Gateway payload hash", "Checkout secureHash signs apiClientID, amount, serviceID, client ID, currency, bill reference, bill description, client name, and secret."],
        ],
        [2700, 6700],
    ))

    body.append(page_break())
    body.append(section_banner("10", "Reconciliation Outcomes", "How settled-payment audit responses are classified."))
    body.append(spacer(120))
    body.append(table(
        ["Outcome", "When used", "Financial effect"],
        [
            ["confirmed", "Remote status is PAID, SUCCESS, COMPLETED, or SETTLED and reference/amount match.", "None. Existing settled records remain unchanged."],
            ["review_required", "Remote status is REVERSED, REFUNDED, REVERSE, or REFUND and reference/amount match.", "None automatically. Sticky audit flag requiring investigation."],
            ["unknown", "Remote response cannot safely prove settlement or reversal.", "None."],
            ["check failure", "Timeout, invalid JSON, HTTP error, malformed or oversized response.", "None. Error is stored in status_check_error."],
        ],
        [2100, 4700, 2600],
    ))

    body.append(page_break())
    body.append(section_banner("11", "Operational Checks", "Commands and SQL useful for supervision, support, and review."))
    body.append(spacer(120))
    body.append(code_block(
        """
# View installed cron entries
crontab -l

# Run posting sync manually
cd /var/www/html/smisportalndudev
/usr/bin/php yii ecitizen-sync/sync

# Run a small reconciliation batch manually
/usr/bin/php yii ecitizen-sync/reconcile-settled 10 60

# Check logs
tail -n 100 runtime/logs/ecitizen-sync.log
tail -n 100 runtime/logs/ecitizen-reconciliation.log
        """
    ))
    body.append(heading("Useful SQL For Review", 2))
    body.append(code_block(
        """
SELECT payment_id,
       "billRefNumber",
       registration_number,
       "amountExpected",
       status,
       paid_amount,
       payment_date,
       gateway_reference,
       synced_trans_id,
       sync_status,
       sync_error,
       reconciliation_status,
       remote_status,
       last_status_checked_at,
       reversal_detected_at
FROM smisportal.ecitizen
ORDER BY payment_id DESC
LIMIT 50;

SELECT payment_id,
       "billRefNumber",
       registration_number,
       "amountExpected",
       remote_status,
       reconciliation_status,
       last_status_checked_at,
       reversal_detected_at,
       status_check_error
FROM smisportal.ecitizen
WHERE reconciliation_status = 'review_required'
ORDER BY reversal_detected_at DESC;
        """
    ))

    body.append(page_break())
    body.append(section_banner("12", "Appendix", "Reference tables for routes and constants."))
    body.append(spacer(120))
    body.append(heading("Main Controller Actions", 2))
    body.append(table(
        ["Action", "Route", "Purpose"],
        [
            ["actionIndex", "GET /ecitizen/payment/index", "Displays student payment form and recent eCitizen requests."],
            ["actionCheckout", "POST /ecitizen/payment/checkout", "Creates a pending invoice and renders the eCitizen iframe checkout."],
            ["actionNotify", "POST /ecitizen/payment/notify", "Receives signed eCitizen callback, validates it, credits portal fee statement, and queues SMIS sync."],
            ["actionSuccess", "GET /ecitizen/payment/success", "Redirects back to invoices."],
            ["actionInvoices", "GET /ecitizen/payment/invoices", "Shows normalized invoice list and action status."],
            ["actionInvoice", "GET /ecitizen/payment/invoice", "Relaunches an unpaid invoice using a secure token."],
            ["actionCompletePayment", "POST /ecitizen/payment/complete-payment", "Server-side fallback that queries eCitizen status and queues payment if settled."],
            ["actionReport", "GET /ecitizen/payment/report", "Optional diagnostic workflow report for authenticated users when enabled."],
        ],
        [2400, 3300, 3700],
    ))
    body.append(heading("Key Constants", 2))
    body.append(table(
        ["Constant or parameter", "Value / meaning"],
        [
            ["PaymentService::PAYMENT_MODE_ID", "12"],
            ["SYNC_PENDING", "0"],
            ["SYNC_DONE", "1"],
            ["SYNC_FAILED", "2"],
            ["PORTAL_ECITIZEN_TRANS_ID_OFFSET", "900000000000"],
            ["Remote settled statuses", "PAID, SUCCESS, COMPLETED, SETTLED"],
            ["Remote reversal statuses", "REVERSED, REFUNDED, REVERSE, REFUND"],
            ["Default invoiceTokenTtl", "900 seconds"],
        ],
        [3400, 6000],
    ))

    return "".join(body)


def plain_language_body() -> str:
    generated = datetime.now().strftime("%Y-%m-%d %H:%M")
    body: list[str] = []

    body.append(cover_panel(
        "eCitizen Fee Payment Guide",
        "Plain-language explanation for staff, supervisors, and module users",
        generated,
        "PLAIN-LANGUAGE GUIDE",
        "For non-technical readers",
    ))
    body.append(spacer(220))
    body.append(snapshot_cards([
        ("What it is", "A bridge between SMIS Portal and eCitizen", "EAF3F8"),
        ("Who uses it", "Students and finance/support staff", "E2F0D9"),
        ("Main result", "Fee payment appears on the student account", "FFF2CC"),
    ]))
    body.append(spacer(180))
    body.append(callout(
        "In simple terms",
        "This module allows a student to start a fee payment from the university portal, complete the payment on eCitizen, and have that payment reflected back in the portal and SMIS finance records.",
        "DDEBF7",
    ))
    body.append(page_break())

    body.append(section_banner("01", "What The Module Does", "A black-box view without code or database detail."))
    body.append(spacer(120))
    body.append(paragraph(
        "The module is like a secure payment bridge. One side is the SMIS Portal where the student starts. "
        "The other side is eCitizen where the payment is completed. After eCitizen confirms payment, the module updates the student's fee records."
    ))
    body.append(table(
        ["Part", "Simple meaning"],
        [
            ["Student portal", "Where the student chooses the amount and starts the payment."],
            ["eCitizen", "Where the actual payment is completed."],
            ["Payment confirmation", "The message eCitizen sends back to say the payment succeeded."],
            ["Portal credit", "The quick update that lets the student see the payment reflected on the portal side."],
            ["SMIS posting", "The official finance update completed in the background."],
            ["Reconciliation", "A later safety check to confirm that completed payments still look correct at eCitizen."],
        ],
        [3000, 6400],
        "548235",
    ))

    body.append(page_break())
    body.append(section_banner("02", "The Payment Journey", "What happens from the student's point of view."))
    body.append(spacer(120))
    body.append(process_flow("Student-facing flow", [
        ("1", "Student opens fee payment page", "305496"),
        ("2", "Student enters amount and phone", "2F75B5"),
        ("3", "Portal opens eCitizen checkout", "548235"),
        ("4", "Student completes payment", "9E480E"),
        ("5", "Payment appears in records", "7030A0"),
    ], "This is the high-level journey a student experiences."))
    body.append(spacer(160))
    for item in [
        "The student does not need to know about the background posting process.",
        "Returning from eCitizen to the portal is not the final proof of payment; the trusted confirmation is the message sent by eCitizen to the system.",
        "Once eCitizen confirmation is received, the portal credits the student's fee statement.",
        "The official SMIS finance posting may complete shortly afterward through the background job.",
    ]:
        body.append(bullet(item))

    body.append(page_break())
    body.append(section_banner("03", "What Staff Should Expect", "Normal behavior after a student pays."))
    body.append(spacer(120))
    body.append(table(
        ["What staff may see", "What it means", "What to do"],
        [
            ["Pending", "The payment request exists but the system has not received successful confirmation yet.", "Ask the student to wait or retry payment if they did not complete it."],
            ["Credited", "eCitizen confirmed payment and the portal fee statement was credited.", "No immediate action. SMIS posting should complete by background sync."],
            ["Settled", "The official SMIS posting has completed.", "Normal final state."],
            ["Sync failed", "The system could not complete official posting automatically.", "Escalate to technical/finance support with the bill reference."],
            ["Review required", "A later eCitizen check found a possible refund or reversal.", "Finance/administration should investigate before changing records."],
        ],
        [2100, 4700, 2600],
        "305496",
    ))
    body.append(callout(
        "Key point",
        "A payment can be genuine even if official SMIS posting is not visible instantly. The system first credits the portal side, then cron completes official posting in the background.",
        "FFF2CC",
        "9E480E",
    ))

    body.append(page_break())
    body.append(section_banner("04", "Behind The Scenes", "Simple explanation of the background jobs."))
    body.append(spacer(120))
    body.append(table(
        ["Background activity", "Plain-language explanation"],
        [
            ["Posting job", "Runs frequently and finishes the official finance records after eCitizen confirms payment."],
            ["Reconciliation job", "Checks completed payments again with eCitizen. It is an audit/safety activity."],
            ["Logs", "System notes that help technical staff investigate if something fails."],
            ["No automatic reversal", "The module does not remove money from a student's fee record automatically. Suspected reversals are flagged for review."],
        ],
        [3000, 6400],
        "7030A0",
    ))
    body.append(process_flow("Back-office flow", [
        ("Confirm", "eCitizen says paid", "305496"),
        ("Credit", "Student sees portal credit", "548235"),
        ("Post", "SMIS finance records finish", "7030A0"),
        ("Audit", "Settled payments rechecked", "9E480E"),
    ], "This is what happens after or around the student's payment."))

    body.append(page_break())
    body.append(section_banner("05", "Common Questions", "Short answers for non-technical conversations."))
    body.append(spacer(120))
    body.append(table(
        ["Question", "Answer"],
        [
            ["Does the portal itself take the money?", "No. The payment is completed through eCitizen. The portal creates the request and records the result."],
            ["Is browser return from eCitizen enough?", "No. The safer confirmation is the signed message sent directly by eCitizen to the system."],
            ["Why can there be a short delay?", "The module separates student-facing confirmation from official SMIS finance posting to make the process safer and more reliable."],
            ["What if a student says they paid but it is still pending?", "Check again after a short wait. If still pending, support should search using the eCitizen bill reference or gateway reference."],
            ["What if a payment is marked review_required?", "Do not reverse records automatically. Finance and technical staff should investigate the eCitizen response and institutional reversal procedure."],
            ["Are secrets or credentials in this guide?", "No. Credentials are configured separately and should not be shared in ordinary documentation."],
        ],
        [3300, 6100],
        "1F4E79",
    ))

    body.append(page_break())
    body.append(section_banner("06", "How To Explain It To Someone", "A short script for meetings or user training."))
    body.append(spacer(120))
    body.append(callout(
        "Suggested explanation",
        "When a student pays through eCitizen, our portal creates a unique payment request and sends the student to eCitizen. eCitizen handles the payment and sends our system a trusted confirmation. Once confirmed, the student's portal fee statement is credited. A background job then completes the official SMIS finance records. Another background check audits completed payments and flags anything unusual for review.",
        "EAF3F8",
        "1F4E79",
    ))
    body.append(table(
        ["Audience", "Emphasize"],
        [
            ["Students", "Start payment in the portal, complete it on eCitizen, and check invoices/fee statement afterward."],
            ["Finance staff", "Credited means confirmed by eCitizen and visible on portal side; Settled means official SMIS posting is complete."],
            ["Supervisors", "The design separates confirmation, posting, and audit checks so payment records are safer and easier to investigate."],
            ["Technical support", "Use references, statuses, and logs to trace where a payment is in the journey."],
        ],
        [2500, 6900],
        "548235",
    ))

    return "".join(body)


def styles_xml() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:style w:type="paragraph" w:default="1" w:styleId="Normal">
    <w:name w:val="Normal"/>
    <w:qFormat/>
    <w:pPr><w:spacing w:after="120" w:line="276" w:lineRule="auto"/></w:pPr>
    <w:rPr><w:rFonts w:ascii="Aptos" w:hAnsi="Aptos"/><w:sz w:val="22"/></w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Title">
    <w:name w:val="Title"/>
    <w:basedOn w:val="Normal"/>
    <w:qFormat/>
    <w:pPr><w:spacing w:after="220"/></w:pPr>
    <w:rPr><w:b/><w:color w:val="1F4E79"/><w:sz w:val="44"/></w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Heading1">
    <w:name w:val="heading 1"/>
    <w:basedOn w:val="Normal"/>
    <w:next w:val="Normal"/>
    <w:qFormat/>
    <w:pPr><w:keepNext/><w:spacing w:before="360" w:after="120"/></w:pPr>
    <w:rPr><w:b/><w:color w:val="1F4E79"/><w:sz w:val="32"/></w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Heading2">
    <w:name w:val="heading 2"/>
    <w:basedOn w:val="Normal"/>
    <w:next w:val="Normal"/>
    <w:qFormat/>
    <w:pPr><w:keepNext/><w:spacing w:before="240" w:after="80"/></w:pPr>
    <w:rPr><w:b/><w:color w:val="2F75B5"/><w:sz w:val="26"/></w:rPr>
  </w:style>
  <w:style w:type="table" w:styleId="TableGrid">
    <w:name w:val="Table Grid"/>
    <w:tblPr>
      <w:tblBorders>
        <w:top w:val="single" w:sz="4" w:color="D5DBE5"/>
        <w:left w:val="single" w:sz="4" w:color="D5DBE5"/>
        <w:bottom w:val="single" w:sz="4" w:color="D5DBE5"/>
        <w:right w:val="single" w:sz="4" w:color="D5DBE5"/>
        <w:insideH w:val="single" w:sz="4" w:color="D5DBE5"/>
        <w:insideV w:val="single" w:sz="4" w:color="D5DBE5"/>
      </w:tblBorders>
    </w:tblPr>
  </w:style>
</w:styles>
"""


def document_xml(body_xml: str) -> str:
    return f"""<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document
  xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
  xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
    <w:body>
    {body_xml}
    <w:sectPr>
      <w:pgSz w:w="11906" w:h="16838"/>
      <w:pgMar w:top="700" w:right="700" w:bottom="700" w:left="700" w:header="420" w:footer="420" w:gutter="0"/>
    </w:sectPr>
  </w:body>
</w:document>
"""


def write_docx(path: Path) -> None:
    content_types = """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
  <Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>
  <Override PartName="/word/settings.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.settings+xml"/>
</Types>
"""
    rels = """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
</Relationships>
"""
    doc_rels = """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"/>
"""
    settings = """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:settings xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:zoom w:percent="100"/>
</w:settings>
"""
    with zipfile.ZipFile(path, "w", zipfile.ZIP_DEFLATED) as docx:
        docx.writestr("[Content_Types].xml", content_types)
        docx.writestr("_rels/.rels", rels)
        docx.writestr("word/_rels/document.xml.rels", doc_rels)
        body_xml = plain_language_body() if path == PLAIN_LANGUAGE_OUTPUT else document_body()
        docx.writestr("word/document.xml", document_xml(body_xml))
        docx.writestr("word/styles.xml", styles_xml())
        docx.writestr("word/settings.xml", settings)


def main() -> None:
    for output in [TECHNICAL_OUTPUT, PLAIN_LANGUAGE_OUTPUT]:
        write_docx(output)
        print(f"Created {output}")


if __name__ == "__main__":
    main()
