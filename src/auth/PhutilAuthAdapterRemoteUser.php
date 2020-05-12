<?php

final class PhutilAuthAdapterRemoteUser extends PhutilAuthAdapter {

  public function getProviderName() {
    return pht('RemoteUser');
  }

  public function getDescriptionForCreate() {
    return pht(
      'Configure a connection to use web server authentication '.
      'credentials to log in to Phabricator.');
  }

  public function getAdapterDomain() {
    return 'self';
  }

  public function getAdapterType() {
    return 'RemoteUser';
  }

  public function getAccountID() {
    return $_SERVER['REMOTE_USER'];
  }

  public function getAccountName() {
    return $this->getAccountID();
  }

  // if LDAP authorization is configured in addition to kerberos
  // authentication, Apache allows putting other attributes from LDAP
  // into the environment prefixed by AUTHORIZE_, so use them if present.
  public function getAccountRealName() {
    // cn is a standard LDAP attibute
    if (!empty($_SERVER['AUTHORIZE_CN']))
      return $_SERVER['AUTHORIZE_CN'];
    // Some installations may prefer to use displayName
    else if (!empty($_SERVER['AUTHORIZE_DISPLAYNAME']))
      return $_SERVER['AUTHORIZE_DISPLAYNAME'];
    // Some installations may populate the name field with the user's real
    // name. This seems to be erroneous, based on Microsoft documenting
    // this attribute as an RDN, so only use it as a last resort.
    else if (!empty($_SERVER['AUTHORIZE_NAME']))
      return $_SERVER['AUTHORIZE_NAME'];
    else
      return parent::getAccountRealName();
  }

  public function getAccountEmail() {
    if (!empty($_SERVER['AUTHORIZE_MAIL']))
      return $_SERVER['AUTHORIZE_MAIL'];
    else
      return parent::getAccountEmail();
  }

  public function getAccountURI() {
    if (!empty($_SERVER['AUTHORIZE_URL']))
      return $_SERVER['AUTHORIZE_URL'];
    else
      return parent::getAccountURI();
  }
}
