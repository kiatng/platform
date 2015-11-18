<?php

namespace Oro\Component\ChainProcessor\Tests\Unit;

use Oro\Component\ChainProcessor\ChainApplicableChecker;
use Oro\Component\ChainProcessor\Context;
use Oro\Component\ChainProcessor\MatchApplicableChecker;
use Oro\Component\ChainProcessor\ProcessorFactoryInterface;
use Oro\Component\ChainProcessor\ProcessorIterator;

class MatchApplicableCheckerTest extends \PHPUnit_Framework_TestCase
{
    public function testMatchApplicableChecker()
    {
        $context = new Context();
        $context->setAction('action1');
        $context->set('class', 'TestCls');
        $context->set('type', 'test');
        $context->set('feature', ['feature1', 'feature3']);

        $processors = [
            'action1' => [
                [
                    'processor'  => 'processor1',
                    'attributes' => []
                ],
                [
                    'processor'  => 'processor2',
                    'attributes' => ['class' => 'TestCls']
                ],
                [
                    'processor'  => 'processor3',
                    'attributes' => ['type' => 'test']
                ],
                [
                    'processor'  => 'processor4',
                    'attributes' => ['class' => 'TestCls', 'type' => 'test']
                ],
                [
                    'processor'  => 'processor5',
                    'attributes' => ['class' => 'TestCls', 'type' => 'test', 'another' => 'val']
                ],
                [
                    'processor'  => 'processor6',
                    'attributes' => ['class' => 'AnotherCls']
                ],
                [
                    'processor'  => 'processor7',
                    'attributes' => ['type' => 'test']
                ],
                [
                    'processor'  => 'processor8',
                    'attributes' => ['class' => 'AnotherCls', 'type' => 'test']
                ],
                [
                    'processor'  => 'processor9',
                    'attributes' => ['class' => 'AnotherCls', 'type' => 'test', 'another' => 'val']
                ],
                [
                    'processor'  => 'processor10',
                    'attributes' => ['class' => 'TestCls']
                ],
                [
                    'processor'  => 'processor11',
                    'attributes' => ['type' => 'another']
                ],
                [
                    'processor'  => 'processor12',
                    'attributes' => ['class' => 'TestCls', 'type' => 'another']
                ],
                [
                    'processor'  => 'processor13',
                    'attributes' => ['class' => 'TestCls', 'type' => 'another', 'another' => 'val']
                ],
                [
                    'processor'  => 'processor14',
                    'attributes' => ['class' => 'TestCls', 'feature' => 'feature1']
                ],
                [
                    'processor'  => 'processor15',
                    'attributes' => ['class' => 'TestCls', 'feature' => 'feature2']
                ],
                [
                    'processor'  => 'processor16',
                    'attributes' => ['class' => 'TestCls', 'feature' => 'feature3']
                ],
            ]
        ];

        $iterator = new ProcessorIterator(
            $processors,
            $context,
            $this->getApplicableChecker(),
            $this->getProcessorFactory()
        );

        $this->assertProcessors(
            [
                'processor1',
                'processor2',
                'processor3',
                'processor4',
                'processor5',
                'processor7',
                'processor10',
                'processor14',
                'processor16',
            ],
            $iterator
        );
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testMatchApplicableCheckerWithCustomApplicableChecker()
    {
        $context = new Context();
        $context->setAction('action1');
        $context->set('class', 'TestCls');
        $context->set('type', 'test');

        $processors = [
            'action1' => [
                [
                    'processor'  => 'processor1',
                    'attributes' => []
                ],
                [
                    'processor'  => 'processor1_disabled',
                    'attributes' => ['disabled' => true]
                ],
                [
                    'processor'  => 'processor2',
                    'attributes' => ['class' => 'TestCls']
                ],
                [
                    'processor'  => 'processor2_disabled',
                    'attributes' => ['disabled' => true, 'class' => 'TestCls']
                ],
                [
                    'processor'  => 'processor3',
                    'attributes' => ['type' => 'test']
                ],
                [
                    'processor'  => 'processor3_disabled',
                    'attributes' => ['disabled' => true, 'type' => 'test']
                ],
                [
                    'processor'  => 'processor4',
                    'attributes' => ['class' => 'TestCls', 'type' => 'test']
                ],
                [
                    'processor'  => 'processor4_disabled',
                    'attributes' => ['disabled' => true, 'class' => 'TestCls', 'type' => 'test']
                ],
                [
                    'processor'  => 'processor5',
                    'attributes' => ['class' => 'TestCls', 'type' => 'test', 'another' => 'val']
                ],
                [
                    'processor'  => 'processor5_disabled',
                    'attributes' => ['disabled' => true, 'class' => 'TestCls', 'type' => 'test', 'another' => 'val']
                ],
                [
                    'processor'  => 'processor6',
                    'attributes' => ['class' => 'AnotherCls']
                ],
                [
                    'processor'  => 'processor6_disabled',
                    'attributes' => ['disabled' => true, 'class' => 'AnotherCls']
                ],
                [
                    'processor'  => 'processor7',
                    'attributes' => ['type' => 'test']
                ],
                [
                    'processor'  => 'processor7_disabled',
                    'attributes' => ['disabled' => true, 'type' => 'test']
                ],
                [
                    'processor'  => 'processor8',
                    'attributes' => ['class' => 'AnotherCls', 'type' => 'test']
                ],
                [
                    'processor'  => 'processor8_disabled',
                    'attributes' => ['disabled' => true, 'class' => 'AnotherCls', 'type' => 'test']
                ],
                [
                    'processor'  => 'processor9',
                    'attributes' => ['class' => 'AnotherCls', 'type' => 'test', 'another' => 'val']
                ],
                [
                    'processor'  => 'processor9_disabled',
                    'attributes' => ['disabled' => true, 'class' => 'AnotherCls', 'type' => 'test', 'another' => 'val']
                ],
                [
                    'processor'  => 'processor10',
                    'attributes' => ['class' => 'TestCls']
                ],
                [
                    'processor'  => 'processor10_disabled',
                    'attributes' => ['disabled' => true, 'class' => 'TestCls']
                ],
                [
                    'processor'  => 'processor11',
                    'attributes' => ['type' => 'another']
                ],
                [
                    'processor'  => 'processor11_disabled',
                    'attributes' => ['disabled' => true, 'type' => 'another']
                ],
                [
                    'processor'  => 'processor12',
                    'attributes' => ['class' => 'TestCls', 'type' => 'another']
                ],
                [
                    'processor'  => 'processor12_disabled',
                    'attributes' => ['disabled' => true, 'class' => 'TestCls', 'type' => 'another']
                ],
                [
                    'processor'  => 'processor13',
                    'attributes' => ['class' => 'TestCls', 'type' => 'another', 'another' => 'val']
                ],
                [
                    'processor'  => 'processor13_disabled',
                    'attributes' => ['disabled' => true, 'class' => 'TestCls', 'type' => 'another', 'another' => 'val']
                ],
            ]
        ];

        $applicableChecker = $this->getApplicableChecker();
        $applicableChecker->addChecker(new NotDisabledApplicableChecker());
        $iterator = new ProcessorIterator(
            $processors,
            $context,
            $applicableChecker,
            $this->getProcessorFactory()
        );

        $this->assertProcessors(
            [
                'processor1',
                'processor2',
                'processor3',
                'processor4',
                'processor5',
                'processor7',
                'processor10',
            ],
            $iterator
        );
    }

    /**
     * @return ChainApplicableChecker
     */
    protected function getApplicableChecker()
    {
        $checker = new ChainApplicableChecker();
        $checker->addChecker(new MatchApplicableChecker());

        return $checker;
    }

    /**
     * @return ProcessorFactoryInterface
     */
    protected function getProcessorFactory()
    {
        $factory = $this->getMock('Oro\Component\ChainProcessor\ProcessorFactoryInterface');
        $factory->expects($this->any())
            ->method('getProcessor')
            ->willReturnCallback(
                function ($processorId) {
                    return new ProcessorMock($processorId);
                }
            );

        return $factory;
    }

    /**
     * @param string[]  $expectedProcessorIds
     * @param \Iterator $processors
     */
    protected function assertProcessors(array $expectedProcessorIds, \Iterator $processors)
    {
        $processorIds = [];
        /** @var ProcessorMock $processor */
        foreach ($processors as $processor) {
            $processorIds[] = $processor->getProcessorId();
        }

        $this->assertEquals($expectedProcessorIds, $processorIds);
    }
}
