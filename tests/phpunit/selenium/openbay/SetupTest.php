<?php
class OpenbaySetupTest extends OpenCartSeleniumTest {
	private $moduleInstalled = false;

	/**
	 * @before
	 */
	protected function before() {
		$this->setBrowser('firefox');
		$this->setBrowserUrl(HTTP_SERVER);
	}

	/**
	 * @after
	 */
	protected function completeTest() {

	}

	public function testSetup() {
		if ($this->moduleInstalled === false) {
			$this->url("admin/");

			$this->byCssSelector('input[name="username"]')->click();
			$this->keys(ADMIN_USERNAME);

			$this->byCssSelector('input[name="password"]')->click();
			$this->keys(ADMIN_PASSWORD);

			$this->byCssSelector('button[type="submit"]')->click();

			$this->moduleInstalled = true;

			$this->waitToLoad('Dashboard');

			// Installing the payment module
			$this->clickOnElement('button-menu');

			$this->waitToAppearAndClick('#extension a');
			$this->waitToAppearAndClick('#extension li:nth-child(3) a');

			$this->waitToLoad('Modules');

			$i = 1;

			for ( ; ; $i++) {
				$element = $this->byCssSelector(".table-striped tbody tr:nth-child($i) td:first-child");

				if ($element->text() == 'OpenBay Pro') {
					break;
				}
			}

			$this->waitToAppearAndClick(".table-striped tbody tr:nth-child($i) td:last-child a.btn-success");

			$this->waitToLoad('Modules', 50000);

			// Go to the OpenBay Pro dashboard
			$this->waitToAppearAndClick('#extension li:nth-child(8) a');
			$this->waitToAppearAndClick('#extension li:nth-child(8) li:first-child a');

			$this->waitToLoad('OpenBay Pro', 50000);

			$this->byCssSelector('#button-install-ebay')->click();

			$this->waitToLoad('OpenBay Pro', 50000);

			$this->byCssSelector('#button-edit-ebay')->click();

			$this->waitToLoad('Dashboard', 50000);

			$this->byCssSelector('#settings-link')->click();

			$this->waitToLoad('Marketplace settings', 50000);

			$this->byCssSelector('#ebay-status option[value="1"]')->click();

			$this->clickOnElement('ebay-token');
			$this->keys(OPENBAY_EBAY_TOKEN);

			$this->clickOnElement('ebay-secret');
			$this->keys(OPENBAY_EBAY_SECRET);

			$this->byCssSelector('button[type="submit"]')->click();
		}
	}

	public function installEbay() {

	}
}
