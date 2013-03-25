<?php

namespace Behat\CommonFormatters;

use Behat\Behat\DataCollector\LoggerDataCollector,
    Behat\Behat\Event\ScenarioEvent,
    Behat\Behat\Event\SuiteEvent,
    Behat\Behat\Event\StepEvent;

/**
 * Formatter that shows failed scenarios with summary.
 *
 * @author A. Martin Llano <anibal.llano@gmail.com>
 */
class FailedScenariosWithSummaryFormatter extends ProgressFormatter
{
    /**
     * {@inheritdoc}
     */
    protected function getDefaultParameters()
    {
        return array();
    }

    /**
     * @see  Symfony\Component\EventDispatcher\EventSubscriberInterface::getSubscribedEvents()
     */
    public static function getSubscribedEvents()
    {
        $events = array('afterScenario', 'afterSuite');

        return array_combine($events, $events);
    }

    /**
     * Listens to "scenario.after" event.
     *
     * @param ScenarioEvent $event
     */
    public function afterScenario(ScenarioEvent $event)
    {
        if (StepEvent::FAILED === $event->getResult()) {
            $scenario = $event->getScenario();
            $this->writeln($scenario->getFile().':'.$scenario->getLine());
        }
    }

    /**
     * Listens to "suite.after" event.
     *
     * @param SuiteEvent $event
     *
     * @uses printSuiteFooter()
     */
    public function afterSuite(SuiteEvent $event)
    {
        $this->printSuiteFooter($event->getLogger());
    }

    /**
     * Prints suite footer information.
     *
     * @param LoggerDataCollector $logger suite logger
     *
     * @uses printSummary()
     * @uses printUndefinedStepsSnippets()
     */
    protected function printSuiteFooter(LoggerDataCollector $logger)
    {
        $this->printSummary($logger);
        $this->printUndefinedStepsSnippets($logger);
    }
}
