# QA tests for QneTwoTrip

The structure is briefly described in codeception.yml

Test for the 1st task is in ./tests/api/, for the 2nd in ./tests/http/

To run tests use the following command:

# ./vendor/bin/codecept run TestSuiteName --steps --html --xml -vvv

where:
./vendor/bin/codecept - (local path to codecept);
TestSuiteName - name of the test's suite (ui or api);
--steps - this command allows you to see all the steps of the test during execution (if you need it);
-vvv - this command allows you to see the intermediate results of the test steps (if you need full information)
--html --xml - this one is for html and xml report generation (in ./_output);
