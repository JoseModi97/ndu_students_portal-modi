[CmdletBinding()]
param(
    [Parameter(Position = 0)]
    [string]$Task = 'list',

    [Parameter(ValueFromRemainingArguments = $true)]
    [string[]]$TaskArguments = @(),

    [switch]$Force
)

$ErrorActionPreference = 'Stop'

$scripts = [ordered]@{
    cleanup       = 'step0_cleanup.php'
    eligibility   = 'step1_eligibility.php'
    apply         = 'step2_apply.php'
    approve1      = 'step3_approve_level1.php'
    approve2      = 'step4_approve_level2.php'
    finalize      = 'step4_finalize.php'
    post          = 'step5_post_caution_refund.php'
    paid          = 'step6_save_paid_refund_voucher.php'
    status        = 'check_status.php'
    debug         = 'debug_record.php'
    verify        = 'verify_accuracy.php'
    checkbanks    = 'check_bank_reference_data.php'
    seedbanks     = 'seed_bank_reference_data.php'
    syncstatus    = 'sync_student_status_cli.php'
}

function Find-ProjectRoot {
    $directory = $PSScriptRoot
    while ($directory) {
        if (Test-Path (Join-Path $directory 'vendor\autoload.php')) {
            return $directory
        }

        $parent = Split-Path -Parent $directory
        if (!$parent -or $parent -eq $directory) {
            break
        }
        $directory = $parent
    }

    throw 'Could not find the project root. Run this script from inside the SMIS Portal project.'
}

function Find-Php {
    if ($env:PHP_EXE) {
        if (!(Test-Path $env:PHP_EXE)) {
            throw "PHP_EXE points to a missing file: $env:PHP_EXE"
        }
        return $env:PHP_EXE
    }

    $command = Get-Command php.exe -ErrorAction SilentlyContinue
    if (!$command) {
        $command = Get-Command php -ErrorAction SilentlyContinue
    }
    if (!$command) {
        throw 'PHP was not found. Add php.exe to PATH or set PHP_EXE to its full path.'
    }

    return $command.Source
}

function Invoke-Automation([string]$Name, [string[]]$Arguments = @()) {
    if (!$scripts.Contains($Name)) {
        throw "Unknown task '$Name'. Run '.\run.ps1 list' to see the available tasks."
    }

    $scriptPath = Join-Path $PSScriptRoot (Join-Path 'automation' $scripts[$Name])
    if (!(Test-Path $scriptPath)) {
        throw "Automation script is missing: $scriptPath"
    }

    Write-Host "`n==> $Name ($($scripts[$Name]))" -ForegroundColor Cyan
    & $script:PhpExecutable $scriptPath @Arguments
    if ($LASTEXITCODE -ne 0) {
        throw "Task '$Name' failed with exit code $LASTEXITCODE."
    }
}

if ($Task.ToLowerInvariant() -eq 'list') {
    Write-Host 'Available refund automation tasks:' -ForegroundColor Cyan
    foreach ($entry in $scripts.GetEnumerator()) {
        Write-Host ('  {0,-14} {1}' -f $entry.Key, $entry.Value)
    }
    Write-Host "`nExamples:"
    Write-Host '  .\run.ps1 eligibility'
    Write-Host '  .\run.ps1 apply mpesa'
    Write-Host '  .\run.ps1 lifecycle -Force'
    exit 0
}

$script:PhpExecutable = Find-Php
$projectRoot = Find-ProjectRoot
Set-Location $projectRoot

if ($Task.ToLowerInvariant() -eq 'lifecycle') {
    if (!$Force) {
        throw "The lifecycle starts with destructive cleanup. Re-run with: .\run.ps1 lifecycle -Force"
    }

    foreach ($name in @('cleanup', 'eligibility', 'apply', 'approve1', 'approve2', 'finalize', 'post', 'paid')) {
        Invoke-Automation $name
    }
    exit 0
}

Invoke-Automation $Task.ToLowerInvariant() $TaskArguments
