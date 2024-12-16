<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 8/12/2024
 * @time: 3:26 PM
 */

namespace app\components;

use stmswitcher\Yii2LdapAuth\Exception\Yii2LdapAuthException;

final class LdapAuth extends \stmswitcher\Yii2LdapAuth\LdapAuth
{
    /**
     * @param string $username
     * @return string|bool
     * @throws Yii2LdapAuthException
     */
    private function findUserDn(string $username): string|bool
    {
        $entry = $this->searchUid($username);

        if (!$entry) {
            return false;
        }

        return $entry['distinguishedname'][0];
    }

    /**
     * @param string $username
     * @param string $password
     * @param string|null $group
     *
     * @return bool
     * @throws Yii2LdapAuthException
     */
    public function authenticate(string $username, string $password, ?string $group = null): bool
    {
        $dn = $this->findUserDn($username);

        if (!@ldap_bind($this->getConnection(), $dn, $password)) {
            return false;
        }

        if (!$group) {
            return true;
        }

        return $this->isUserInAGroup($dn, $group);
    }

    /**
     * @param string $uid
     *
     * @return array|null Data from LDAP or null
     * @throws Yii2LdapAuthException
     */
    public function searchUid(string $uid): ?array
    {
        $uid = str_replace(['/', '\\'], '', $uid);

        $filter = "(CN=$uid)";

        $result = ldap_search(
            $this->getConnection(),
            $this->baseDn,
            $filter
        );

        $entries = ldap_get_entries($this->getConnection(), $result);

        return $entries[0] ?? null;
    }
}