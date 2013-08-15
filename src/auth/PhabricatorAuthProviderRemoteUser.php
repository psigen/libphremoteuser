<?php

final class PhabricatorAuthProviderRemoteUser
  extends PhabricatorAuthProvider {

  private $adapter;

  public function getProviderName() {
    return pht('RemoteUser');
  }

  public function getDescriptionForCreate() {
    return pht(
      'Configure a server to use web server built-in authentication '.
      'for user credentials to log in to Phabricator.');
  }

  public function getAdapter() {
    if (!$this->adapter) {
      $adapter = new PhutilAuthAdapterRemoteUser();
      $this->adapter = $adapter;
    }
    return $this->adapter;
  }

  public function isLoginFormAButton() {
    return true;
  }

  protected function renderLoginForm(AphrontRequest $request, $mode) {
    $viewer = $request->getUser();

    if ($mode == 'link') {
      $button_text = pht('Link External Account');
    } else if ($mode == 'refresh') {
      $button_text = pht('Refresh Account Link');
    } else if ($this->shouldAllowRegistration()) {
      $button_text = pht('Login or Register');
    } else {
      $button_text = pht('Login');
    }

    $icon = id(new PHUIIconView())
      ->setSpriteSheet(PHUIIconView::SPRITE_LOGIN)
      ->setSpriteIcon($this->getLoginIcon());

    $button = id(new PHUIButtonView())
        ->setSize(PHUIButtonView::BIG)
        ->setColor(PHUIButtonView::GREY)
        ->setIcon($icon)
        ->setText($button_text)
        ->setSubtext($this->getProviderName());

    $content = array($button);
    $uri = $this->getLoginURI();

    return phabricator_form(
      $viewer,
      array(
        'method' => 'GET',
        'action' => (string)$uri,
      ),
      $content);
  }

  public function processLoginRequest(
    PhabricatorAuthLoginController $controller) {

    $request = $controller->getRequest();
    $adapter = $this->getAdapter();
    $account = null;
    $response = null;

    try {
      $account_id = $adapter->getAccountID();
    } catch (Exception $ex) {
      // TODO: Handle this in a more user-friendly way.
      throw $ex;
    }

    if (!strlen($account_id)) {
      $response = $controller->buildProviderErrorResponse(
        $this,
        pht(
          'The web server failed to provide an account ID.'));

      return array($account, $response);
    }

    return array($this->loadOrCreateAccount($account_id), $response);
  }
}