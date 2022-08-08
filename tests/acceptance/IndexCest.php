<?php

class IndexCest
{
    //Check if front page is loaded
    public function homePageRoute(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Crypto Dashboard');
    }

    //Reload page to check for error when polygon api is called within less than a minute
    public function errorForApi(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Too many calls to the polygon api, wait one minute before trying again.');
    }

    //Check if prices match for certain date in EUR
    public function checkPriceEUR(AcceptanceTester $I)
    {
        $this->errorForApi($I);
        //Wait 1min to not get api overload error
        sleep(60);
        $I->amOnPage('/');
        $I->see('Crypto Dashboard');
        $I->fillField('date', '2022-08-01');
        $I->selectOption('curr', 'EUR');
        sleep(60);
        $I->click('Search');
        $I->see('22831.2', '.btc');
        $I->see('22684.26', '.btc');
    }

    //Check if prices match for certain date in EUR
    public function checkPriceUSD(AcceptanceTester $I)
    {
        $this->errorForApi($I);
        //Wait 1min to not get api overload error
        sleep(60);
        $I->amOnPage('/');
        $I->see('Crypto Dashboard');
        $I->fillField('date', '2022-08-01');
        $I->selectOption('curr', 'USD');
        sleep(60);
        $I->click('Search');
        $I->see('23291', '.btc');
        $I->see('23273.86', '.btc');
    }

    //Reload page to check for error when polygon api is called within less than a minute
    public function errorForDate(AcceptanceTester $I)
    {
        $this->errorForApi($I);
        //Wait 1min to not get api overload error
        sleep(60);
        $I->amOnPage('/');
        $I->see('Crypto Dashboard');
        $I->fillField('date', '2018-08-01');
        $I->selectOption('curr', 'USD');
        sleep(60);
        $I->click('Search');
        $I->see('Choose a date between today and one year ago.');
    }
}
