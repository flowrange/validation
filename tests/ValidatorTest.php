<?php

namespace Flowrange\Validation\Tests;

use Flowrange\Validation\Validator;
use Flowrange\Validation\ErrorsBag;
use Flowrange\Validation\RuleCheckResult;

use Flowrange\Validation\Tests\ValidatorTest\RuleBuilder;
use Flowrange\Validation\Tests\ValidatorTest\FilterBuilder;

use function \Flowrange\Validation\Rule\rules;

/**
 * Validator tests
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase
{


    /**
     * Validator we're testing
     * @var \Flowrange\Validation\Validator
     */
    private $validator;


    /**
     * setUp
     */
    public function setUp()
    {
        $this->validator = new Validator();
    }


    /**
     * Returns a RuleBuilder
     * @return RuleBuilder
     */
    public function mockRule()
    {
        return new RuleBuilder($this);
    }


    /**
     * Returns a FilterBuilder
     * @return FilterBuilder
     */
    public function mockFilter()
    {
        return new FilterBuilder($this);
    }


    public function testConstructor()
    {
        $this->assertInstanceOf(
            'Flowrange\Validation\Validator',
            $this->validator);
    }


    public function testCreateReturnsValidator()
    {
        $v = Validator::create();

        $this->assertInstanceOf(
            'Flowrange\Validation\Validator',
            $v);
    }


    public function testDataIsNotValidByDefault()
    {
        $this->assertFalse(
            $this->validator->check(
                [
                    'field1' => 'value'
                ]));
    }


    public function testCheckChecksDataAndReturnsTrueIfDataValid()
    {
        $validRule = $this->mockRule()->expects('value')->yields(true, 'value')->build();

        $this->validator->addRules([
            'field1' => rules($validRule)
        ]);

        $this->assertTrue(
            $this->validator->check(
                [
                    'field1' => 'value'
                ]));
    }


    public function testCheckChecksMultipleRulesAndStopsAtFirstInvalid()
    {
        $rule1 = $this->mockRule()->expects('value')->yields(true,  'value')->build();
        $rule2 = $this->mockRule()->expects('value')->yields(false, 'value', 'error', false)->build();
        $rule3 = $this->mockRule()->shouldNotBeCalled()->build();

       $this->validator->addRules([
           'field1' => rules($rule1, $rule2, $rule3)
       ]);

        $this->assertFalse(
            $this->validator->check(
                [
                    'field1' => 'value'
                ]));
    }


    public function testCheckChecksAdditionalRulesIfRuleIsInvalidButSetsForwardNext()
    {
        $rule1 = $this->mockRule()->expects('value')->build();
        $rule2 = $this->mockRule()->expects('value')->yields(false, 'value', 'error', true)->build();
        $rule3 = $this->mockRule()->expects('value')->build();

        $this->validator->addRules([
            'field1' => rules($rule1, $rule2, $rule3)
        ]);

        $this->assertFalse(
            $this->validator->check(
                [
                    'field1' => 'value'
                ]));
    }


    public function testCheckStopsIfARuleIsValidButSetsForwardNextToFalse()
    {
        $rule1 = $this->mockRule()->expects('value')->build();
        $rule2 = $this->mockRule()->expects('value')->yields(true, 'value', 'error', false)->build();
        $rule3 = $this->mockRule()->shouldNotBeCalled()->build();

        $this->validator->addRules([
            'field1' => rules($rule1, $rule2, $rule3)
        ]);

        $this->assertTrue(
            $this->validator->check(
                [
                    'field1' => 'value'
                ]));
    }


    public function testCheckChecksMultipleFields()
    {
        $rule = $this->mockRule()
                     ->expects('value1', 'value2')
                     ->build();

       $this->validator->addRules([
           'field1' => rules($rule),
           'field2' => rules($rule)
       ]);

        $this->assertTrue(
            $this->validator->check(
                [
                    'field1' => 'value1',
                    'field2' => 'value2'
                ]));
    }


    public function testFieldsCanBeNested()
    {
        $rule = $this->mockRule()
                     ->expects('value1', 'value2')
                     ->build();

        $this->validator->addRules([
            'field' => [
                'field1' => rules($rule),
                'field2' => rules($rule)]]);

        $this->assertTrue(
            $this->validator->check(
                [
                    'field' => [
                        'field1' => 'value1',
                        'field2' => 'value2'
                    ]
                ]));
    }


    public function testFieldsCanBeDeeplyNested()
    {
        $rule = $this->mockRule()
                     ->expects('value1', 'value2', 'value3', 'value4')
                     ->build();

        $this->validator->addRules([
            'field' => [
                'field1' => [
                    'field2' => [
                        'field3' => rules($rule),
                        'field4' => rules($rule)
                    ],
                    'field5' => rules($rule)
                ],
                'field6' => rules($rule)
            ]
        ]);

        $this->assertTrue(
            $this->validator->check(
                [
                    'field' => [
                        'field1' => [
                            'field2' => [
                                'field3' => 'value1',
                                'field4' => 'value2'
                            ],
                            'field5' => 'value3'
                        ],
                        'field6' => 'value4'
                    ]
                ]));
    }


    public function testFieldCanBeRowOfValues()
    {
        $rule = $this->mockRule()
                     ->expects('value1', 'value2')
                     ->build();

        $this->validator->addRules([
            'field[]' => rules($rule)
        ]);

        $this->assertTrue(
            $this->validator->check(
                [
                    'field' => [
                        'value1',
                        'value2'
                    ]
                ]));
    }


    public function testRowsCanBeNested()
    {
        $rule = $this->mockRule()
                     ->expects('value1', 'value2', 'value3', 'value4')
                     ->build();

        $this->validator->addRules([
           'field' => [
               'key1[]' => [
                   'key2[]' => rules($rule)
               ]
           ]
        ]);

        $this->assertTrue(
            $this->validator->check(
                [
                    'field' => [
                        'key1' => [
                            [
                                'key2' => [
                                    'value1',
                                    'value2'
                                ],
                            ],
                            [
                                'key2' => [
                                    'value3',
                                    'value4'
                                ]
                            ]
                        ]
                    ]
                ]));
    }


    public function testRowsCanHaveSubField()
    {
        $rule1 = $this->mockRule()
                      ->expects('value1', 'value3')
                      ->build();

        $rule2 = $this->mockRule()
                      ->expects('value2', 'value4')
                      ->build();

        $this->validator->addRules([
            'field[]' => [
                'field1' => rules($rule1),
                'field2' => rules($rule2)
            ]
        ]);

        $this->assertTrue(
            $this->validator->check(
                [
                    'field' => [
                            [
                                'field1' => 'value1',
                                'field2' => 'value2'
                            ],
                            [
                                'field1' => 'value3',
                                'field2' => 'value4'
                            ]]
                ]));
    }


    public function testRowsCanHaveSubFieldAndRowsSubFields()
    {
        $rule1 = $this->mockRule()
                      ->expects('value1', 'value5')
                      ->build();

        $rule2 = $this->mockRule()
                      ->expects('value2', 'value4', 'value6')
                      ->build();

        $rule3 = $this->mockRule()
                      ->expects('value3', 'value5', 'value7')
                      ->build();

        $this->validator->addRules([
            'field[]' => [
                'field1' => rules($rule1),
                'field2[]' => [
                    'field11' => rules($rule2),
                    'field12' => rules($rule3)
                ]
            ]
        ]);


        $this->assertTrue(
            $this->validator->check(
            [
                'field' => [
                    [
                        'field1' => 'value1',
                        'field2' => [
                            [
                                'field11' => 'value2',
                                'field12' => 'value3'
                            ],
                            [
                                'field11' => 'value4',
                                'field12' => 'value5'
                            ]
                        ],
                    ],
                    [
                        'field1' => 'value5',
                        'field2' => [
                            [
                                'field11' => 'value6',
                                'field12' => 'value7'
                            ]
                        ]
                    ]
                ]
            ]));
    }


    public function testRulesCanBeAppliedToRowsParent()
    {
        $rule1 = $this->mockRule()->expects(
            [
                [
                    'field21' => 'value',
                    'field22' => 'value'
                ],
                [
                    'field21' => 'value',
                    'field22' => 'value'
                ]
            ])->build();

        $rule2 = $this->mockRule()->build();

        $this->validator->addRules([
            'field1' => [
                'field2' => rules($rule1),
                'field2[]' => [
                    'field21' => rules($rule2),
                    'field22' => rules($rule2)
                ]
            ]
        ]);

        $this->validator->check([
            'field1' => [
                'field2' => [
                    [
                        'field21' => 'value',
                        'field22' => 'value'
                    ],
                    [
                        'field21' => 'value',
                        'field22' => 'value'
                    ]
                ]
            ]
        ]);
    }


    public function testAddRulesMergesRules()
    {
        $rule1 = $this->mockRule()->expects('value1')->yields(true, 'value1')->build();
        $rule2 = $this->mockRule()->expects('value2')->yields(true, 'value2')->build();

        $this->validator->addRules([
            'field' => [
                'field1' => rules($rule1)
            ]
        ]);
        $this->validator->addRules([
            'field' => [
                'field2' => rules($rule2)
            ]
        ]);

        $this->assertTrue(
            $this->validator->check(
                [
                    'field' => [
                        'field1' => 'value1',
                        'field2' => 'value2'
                    ]
                ]));
    }


    public function testGetDataReturnsValuesFromRules()
    {
        $rule1 = $this->mockRule()->expects('x')->yields(false, 'value1')->build();
        $rule2 = $this->mockRule()->expects('y')->yields(true, 'value2')->build();
        $rule3 = $this->mockRule()->expects('value2')->yields(true, 'value3')->build();

        $this->validator->addRules([
            'field1' => rules($rule1),
            'field2' => rules($rule2, $rule3),
        ]);

        $this->validator->check(
            [
                'field1' => 'x',
                'field2' => 'y']);

        $this->assertEquals(
            [
                'field1' => 'value1',
                'field2' => 'value3'],
            $this->validator->getData());
    }


    public function testGetDataWorksWithArrayKeys()
    {
        $rule1 = $this->mockRule()->expects('x')->yields(true, 'other1')->build();
        $rule2 = $this->mockRule()->expects('other1')->yields(true, 'other2')->build();
        $rule3 = $this->mockRule()->expects('y')->yields(true, 'other3')->build();
        $rule4 = $this->mockRule()->expects('z')->yields(true, 'other4')->build();

       $this->validator->addRules([
           'field1' => [
               'field2[]' => [
                   'field3' => rules($rule1, $rule2),
                   'field4' => rules($rule3)
               ],
               'field5' => rules($rule4)
           ]
       ]);

        $this->validator->check(
            [
                'field1' => [
                    'field2' => [
                        [
                            'field3' => 'x',
                            'field4' => 'y'
                        ]
                    ],
                    'field5' => 'z'
                ]
            ]);

        $this->assertEquals(
            [
                'field1' => [
                    'field2' => [
                        [
                            'field3' => 'other2',
                            'field4' => 'other3'
                        ]
                    ],
                    'field5' => 'other4'
                ]
            ],
            $this->validator->getData());
    }


    public function testValidatorUsesAndDefinesEmptyStringForMissingValues()
    {
        $rule1 = $this->mockRule()->build();

        $this->validator->addRules([
            'field1' => rules($rule1),
            'field2' => rules($rule1)

        ]);

        $this->validator->check([]);

        $this->assertSame(
            [
                'field1' => '',
                'field2' => ''
            ],
            $this->validator->getData());
    }


    public function testValidatorUsesEmptyStringForMissingArrayValues()
    {
        $rule = $this->mockRule()->build();

       $this->validator->addRules([
           'field' => [
               'key' => rules($rule)
           ]
       ]);

        $this->validator->check([]);

        $this->assertSame(
            [
                'field' => [
                    'key' => ''
                ]
            ],
            $this->validator->getData());
    }


    public function testValidatorUsesEmptyArrayForMissingRowsArrayValues()
    {
        $rule = $this->mockRule()->build();

        $this->validator->addRules([
            'field[]' => rules($rule)
        ]);

        $this->validator->check([]);

        $this->assertSame(
            [
                'field' => []
            ],
            $this->validator->getData());
    }


    public function testValidatorUseEmptyArraysAndStringForMissingDataWithSomeDataAlreadyHere()
    {
        $rules = rules($this->mockRule()->build());

        $this->validator->addRules([
            'field1' => $rules,
            'field2' => [
                'field21' => $rules,
                'field22' => $rules
            ],
            'field3[]' => [
                'field4' => $rules,
                'field5[]' => [
                    'field51' => $rules,
                    'field52[]' => $rules
                ]
            ]
        ]);

        $this->validator->check([
            'field1' => '',
            'field3' => [
                [
                    'field4' => ''
                ],
                [
                    'field5' => [
                        [
                            'field52' => []
                        ],
                        [
                            'field51' => ''
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertSame(
            [
                'field1' => '',
                'field2' => [
                    'field21' => '',
                    'field22' => ''
                ],
                'field3' => [
                    [
                        'field4' => '',
                        'field5' => []
                    ],
                    [
                        'field4' => '',
                        'field5' => [
                            [
                                'field51' => '',
                                'field52' => []
                            ],
                            [
                                'field51' => '',
                                'field52' => []
                            ]
                        ]
                    ]
                ]
            ],
            $this->validator->getData());
    }


    public function testCheckAppliesFiltersToValues()
    {
        $filter = $this->mockFilter()->yields('value1')->build();

        $this->validator->addRules([
            'field1' => rules($filter),
        ]);


        $this->validator->check(
            [
                'field1' => 'value']);

        $this->assertEquals(
            [
                'field1' => 'value1'],
            $this->validator->getData());
    }


    public function testValidatorFiltersUsingPreviousValues()
    {
        $rule   = $this->mockRule()->expects('value')->yields(true, 'some-value')->build();
        $filter = $this->mockFilter()->expects('some-value')->yields('final value')->build();

        $this->validator->addRules([
            'field' => rules($rule, $filter)
        ]);

        $this->validator->check(['field' => 'value']);

        $this->assertSame(
            [
                'field' => 'final value'
            ],
            $this->validator->getData());
    }


    public function testGetErrorsReturnEmptyErrorsBagIfValid()
    {
        $validRule = $this->mockRule()->build();

        $this->validator->addRules([
            'field1' => rules($validRule)
        ]);

        $this->validator->check([]);

        $this->assertEquals(
            new ErrorsBag(),
            $this->validator->getErrors());
    }


    public function testGetErrorsReturnsErrorsBagWithErrors()
    {
        $rule1 = $this->mockRule()->expects('')->yields(false, '', 'error 1')->build();
        $rule2 = $this->mockRule()->expects('')->yields(false, '', 'error 2')->build();
        $rule3 = $this->mockRule()->expects('')->yields(false, '', 'error 3')->build();

        $this->validator->addRules([
            'field1' => rules($rule1, $rule2),
            'field2' => rules($rule3)
        ]);

        $this->validator->check([]);

        $errors = [
            'field1' => ['error 1', 'error 2'],
            'field2' => ['error 3']
        ];

        $this->assertEquals(
            new ErrorsBag($errors, $errors),
            $this->validator->getErrors());
    }


    public function testGetErrorsReturnsErrorsBagWithErrorsForNestedFields()
    {
        $rule = $this->mockRule()
                     ->expects('')->yields(false, '', 'error')
                     ->expects('')->yields(false, '', 'error')
                     ->build();

        $this->validator->addRules([
            'field1' => [
                'field2' => [
                    'field3' => rules($rule)
                ],
                'field4' => rules($rule)
            ]
        ]);

        $this->validator->check([]);

        $errors = [
            'field1[field2][field3]' => ['error'],
            'field1[field4]'         => ['error']
        ];

        $this->assertEquals(
            new ErrorsBag($errors, $errors),
            $this->validator->getErrors());
    }


    public function testGetErrorsReturnsErrorsBagWithRowsErrorInGenericErrors()
    {
        $rule1 = $this->mockRule()->yields(false, null, 'error 1', false)->build();
        $rule2 = $this->mockRule()->yields(false, null, 'error 2', false)->build();

        $this->validator->addRules([
            'field1[]' => [
                'field2' => rules($rule1),
                'field3[]' => [
                    'field4' => rules($rule1),
                    'field5' => rules($rule2)
                ]
            ],
            'field6[]' => rules($rule1),
            'field7' => rules($rule1)
        ]);

        $this->validator->check([
            'field1' => [
                [
                    'field3' => [
                        [], []
                    ]
                ],
                [
                    'field2' => ''
                ]
            ],
            'field6' => [1, 2, 3]
        ]);

        $globalErrors = [
            'field1[][field2]'  => ['error 1'],
            'field1[][field3][][field4]' => ['error 1'],
            'field1[][field3][][field5]' => ['error 2'],
            'field6[]'  => ['error 1'],
            'field7'    => ['error 1']
        ];

        $fieldsErrors = [
            'field1[0][field2]' => ['error 1'],
            'field1[0][field3][0][field4]' => ['error 1'],
            'field1[0][field3][0][field5]' => ['error 2'],
            'field1[0][field3][1][field4]' => ['error 1'],
            'field1[0][field3][1][field5]' => ['error 2'],
            'field1[1][field2]' => ['error 1'],
            'field6[0]' => ['error 1'],
            'field6[1]' => ['error 1'],
            'field6[2]' => ['error 1'],
            'field7'    => ['error 1']
        ];

        $this->assertEquals(
            new ErrorsBag($globalErrors, $fieldsErrors),
            $this->validator->getErrors());
    }


    public function testPostCheckListenerCanBeAdded()
    {
        $this->validator
             ->addRules([
                 'field1' => rules($this->mockRule()->build())
             ])
             ->postCheck(
                 function (&$data) {
                    $data['field1'] = 'other value';
                 });

        $this->validator->check(['field1' => 'some value']);

        $this->assertEquals(
            ['field1' => 'other value'],
            $this->validator->getData());
    }


    public function testPostCheckMultipleListenerCanBeAdded()
    {
        $this->validator
             ->addRules([
                 'field1' => rules($this->mockRule()->build())
             ])
             ->postCheck(
                 function (&$data) {
                    $data['field1'] = 'other value';
                 })
             ->postCheck(
                 function (&$data) {
                    $data['field1'] = 'third value';
                 });

        $this->validator->check(['field1' => 'some value']);

        $this->assertEquals(
            ['field1' => 'third value'],
            $this->validator->getData());
    }

}
