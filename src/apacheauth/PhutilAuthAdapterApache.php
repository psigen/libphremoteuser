<?php

final class PhutilAuthAdapterApache extends PhutilAuthAdapter {

  public function getProviderName() {
    return pht('Apache');
  }

  public function getDescriptionForCreate() {
    return pht(
      'Configure a connection to use Apache for authentication '.
      'credentials to log in to Phabricator.');
  }

  public function getAdapterDomain() {
    return 'self';
  }

  public function getAdapterType() {
    return 'Apache';
  }

  public function getAccountID() {
    return $_SERVER['REMOTE_USER'];
  }

  public function getAccountName() {
    return $this->getAccountID();
  }

}
