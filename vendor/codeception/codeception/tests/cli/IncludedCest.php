<?php
class IncludedCest
{

    public function _before()
    {
        \Codeception\Util\FileSystem::doEmptyDir('tests/data/included/_log');
        file_put_contents('tests/data/included/_log/.gitkeep', '');
    }

    /**
     * @param CliGuy $I
     */
    protected function moveToIncluded(\CliGuy $I)
    {
        $I->amInPath('tests/data/included');
    }

    /**
     * @before moveToIncluded
     * @param CliGuy $I
     */
    public function runSuitesFromIncludedConfigs(\CliGuy $I)
    {
        $I->executeCommand('run');
        $I->seeInShellOutput('[Jazz]');
        $I->seeInShellOutput('Jazz.http Tests');
        $I->seeInShellOutput('[Jazz\Pianist]');
        $I->seeInShellOutput('Jazz\Pianist.http Tests');
        $I->seeInShellOutput('[Shire]');
        $I->seeInShellOutput('Shire.http Tests');
    }

    /**
     * @before moveToIncluded
     * @param CliGuy $I
     */
    public function runTestsFromIncludedConfigs(\CliGuy $I)
    {
        $ds = DIRECTORY_SEPARATOR;
        $I->executeCommand("run jazz{$ds}tests{$ds}http{$ds}DemoCept.php", false);

        // Suite is not run
        $I->dontSeeInShellOutput('[Jazz]');

        // DemoCept tests are run
        $I->seeInShellOutput('Jazz.http Tests');
        $I->seeInShellOutput('DemoCept');

        // Other include tests are not run
        $I->dontSeeInShellOutput('[Shire]');
        $I->dontSeeInShellOutput('Shire.http Tests');
        $I->dontSeeInShellOutput('[Jazz\Pianist]');
        $I->dontSeeInShellOutput('Jazz\Pianist.http Tests');
    }

    /**
     * @before moveToIncluded
     * @param CliGuy $I
     */
    public function runTestsFromIncludedConfigsNested(\CliGuy $I)
    {
        $I->executeCommand('run jazz/pianist/tests/http/PianistCept.php', false);

        // Suite is not run
        $I->dontSeeInShellOutput('[Jazz\Pianist]');

        // DemoCept tests are run
        $I->seeInShellOutput('Jazz\Pianist.http Tests');
        $I->seeInShellOutput('PianistCept');

        // Other include tests are not run
        $I->dontSeeInShellOutput('[Shire]');
        $I->dontSeeInShellOutput('Shire.http Tests');
        $I->dontSeeInShellOutput('[Jazz]');
        $I->dontSeeInShellOutput('Jazz.http Tests');
    }

    /**
     * @before moveToIncluded
     * @param CliGuy $I
     */
    public function runTestsFromIncludedConfigsSingleTest(\CliGuy $I)
    {
        $ds = DIRECTORY_SEPARATOR;
        $I->executeCommand("run jazz{$ds}tests{$ds}unit{$ds}SimpleTest.php:testSimple", false);

        // Suite is not run
        $I->dontSeeInShellOutput('[Jazz]');

        // SimpleTest:testSimple is run
        $I->seeInShellOutput('Jazz.unit Tests');
        $I->dontSeeInShellOutput('Jazz.http Tests');
        $I->seeInShellOutput('SimpleTest');

        //  SimpleTest:testSimpler is not run
        $I->dontSeeInShellOutput('SimplerTest');

        // Other include tests are not run
        $I->dontSeeInShellOutput('[Shire]');
        $I->dontSeeInShellOutput('Shire.http Tests');
        $I->dontSeeInShellOutput('[Jazz\Pianist]');
        $I->dontSeeInShellOutput('Jazz\Pianist.http Tests');
    }

    /**
     * @before moveToIncluded
     * @param CliGuy $I
     */
    public function runIncludedWithXmlOutput(\CliGuy $I)
    {
        $I->executeCommand('run --xml');
        $I->amInPath('_log');
        $I->seeFileFound('report.xml');
        $I->seeInThisFile('<testsuite name="Jazz.http" tests="1" assertions="1"');
        $I->seeInThisFile('<testsuite name="Jazz\Pianist.http" tests="1" assertions="1"');
        $I->seeInThisFile('<testsuite name="Shire.http" tests="1" assertions="1"');
        $I->seeInThisFile('<testcase name="Hobbit"');
        $I->seeInThisFile('<testcase name="Demo"');
        $I->seeInThisFile('<testcase name="Pianist"');
    }

    /**
     * @before moveToIncluded
     * @param CliGuy $I
     */
    public function runIncludedWithHtmlOutput(\CliGuy $I)
    {
        $I->executeCommand('run --html');
        $I->amInPath('_log');
        $I->seeFileFound('report.html');
        $I->seeInThisFile('Codeception Results');
        $I->seeInThisFile('Jazz.http Tests');
        $I->seeInThisFile('Check that jazz musicians can add numbers');
        $I->seeInThisFile('Jazz\Pianist.http Tests');
        $I->seeInThisFile('Check that jazz pianists can add numbers');
        $I->seeInThisFile('Shire.http Tests');
    }

    /**
     * @before moveToIncluded
     * @group coverage
     * @param CliGuy $I
     */
    public function runIncludedWithCoverage(\CliGuy $I)
    {
        $I->executeCommand('run --coverage-xml');
        $I->amInPath('_log');
        $I->seeFileFound('coverage.xml');
        $I->seeInThisFile('BillEvans" namespace="Jazz\Pianist">');
        $I->seeInThisFile('Musician" namespace="Jazz">');
        $I->seeInThisFile('Hobbit" namespace="Shire">');
    }

    /**
     * @before moveToIncluded
     * @param CliGuy $I
     */
    public function buildIncluded(\CliGuy $I)
    {
        $I->executeCommand('build');
        $I->seeInShellOutput('generated successfully');
        $I->seeInShellOutput('Jazz\\TestGuy');
        $I->seeInShellOutput('Jazz\\Pianist\\TestGuy');
        $I->seeInShellOutput('Shire\\TestGuy');
    }
}
