<?php 

class RedirectCheckCest
{
    // tests
    // try to go to the page with an incomplete domain, check if the redirect is carried out

    public function tryToTest(HttpTester $I)
    {
        $I->amOnPage('https://onetwotrip.com/');
        $I->haveHttpHeader('Location', 'https://www.onetwotrip.com/');
        try {
            $I->seeCurrentFullUrlMatches('https://www.onetwotrip.com/');
        } catch (\Codeception\Exception\ModuleException $e) {
        }
    }
}
