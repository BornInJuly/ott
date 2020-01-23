<?php 

class QueryCheckCest
{
    // prehistory
    // go to the bus search page and begin to enter a request in the departure field

    public function _before(ApiTester $I)
    {
        $I->amOnPage('/bus/ru/');
        $I->seeElement('//input[@type="departure"]');
        $I->fillField('//input[@type="departure"]', 'Пар');
    }

    // tests
    // try to send a request to api with data from the field, check what comes in the response

    public function tryToTest(ApiTester $I)
    {
        $query = $I->grabValueFrom('//input[@type="departure"]');
        $I->sendGET('_bus/geo/suggest', [
            'query' => $query,
            'limit' => '10'
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'data' => [[
                'name' => 'string:regex(~'.$query.'~)',
                'district' => 'string|null',
                'region' => 'string|null',
                'country' => 'string',
                'rating' => 'integer|float',
                'geopointId' => 'integer',
                'trnslt' => 'string|null',
                'type' => 'string|null'
            ]]
        ]);
    }
}
