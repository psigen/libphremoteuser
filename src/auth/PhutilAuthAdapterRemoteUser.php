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
    return 'Apache';
  }

  public function getAccountID() {
    return $_SERVER['REMOTE_USER'];
  }

  public function getAccountName() {
    return $this->getAccountID();
  }

}
