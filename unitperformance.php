<?php
/**
 * Created by PhpStorm.
 * User: sreedhardevireddy
 * Date: 22/10/2017
 * Time: 09:23
 *
 * Design Pattern used: Decorator
 *
 * A decorator allows me to dynamically extend the behavior of a particular object at runtime
 *
 * To avoid violating the Open Close Principals. Object and component should be open for extension
 * but closed for modification. This is pretty much useful when we need to expand the functionality to few more lines
 *
 * Purpose: To calculate Unit Performance for all the lines / for individual lines in Unit A and B
 *
 * @Todo Should try the same with Composition approach aswell.
 *
 */

/**
 * Interface UnitPerformance
 */

interface UnitPerformance
{
    public function getLineOutput();
    public function getTargetPerShift();
    public function getDescription();
}

/**
 * Class BuildLines - BA & BB
 */
class BuildLines implements UnitPerformance
{
    public function getLineOutput()
    {
        return 850;
    }

    public function getTargetPerShift()
    {
        return 900;
    }

    public function getDescription()
    {
        return ' Build Lines Performance';
    }
}

/**
 * Class SmartBuildLines - UK,EU Smart Builds
 *
 * Decorator forced to implement the same contract ie Lineoutput,Description and targets
 */

class SmartBuildLines implements UnitPerformance
{

    protected $unitPerformance;

    /**
     * SmartBuildLines constructor.
     * @param UnitPerformance $unitPerformance
     * This specifically allows us to build the objects at the runtime rather than new instance each time
     */

    function __construct(UnitPerformance $unitPerformance)
    {
        $this->unitPerformance = $unitPerformance;
    }

    public function getLineOutput()
    {
        return 1000 + $this->unitPerformance->getLineOutput();
    }

    public function getTargetPerShift()
    {
        return 1000 + $this->unitPerformance->getTargetPerShift();
    }

    public function getDescription()
    {
        return $this->unitPerformance->getDescription().' ,  Smart Build Lines Performance';
    }
}


/**
 * Class CalibrationLines - Null,Flow,Multi NUll Chambers
 */

class CalibrationLines implements UnitPerformance
{

    protected $unitPerformance;

    /**
     * CalibrationLines constructor.
     * @param UnitPerformance $unitPerformance
     * Decorator Must accept with in the constructor some instance or implementation of the same contract here
     * This specifically allows us to build the objects
     */

    function __construct(UnitPerformance $unitPerformance)
    {
        $this->unitPerformance = $unitPerformance;
    }

    public function getLineOutput()
    {
        return 1500 + $this->unitPerformance->getLineOutput();
    }

    public function getTargetPerShift()
    {
        return 1600 + $this->unitPerformance->getTargetPerShift();
    }

    public function getDescription()
    {
        return $this->unitPerformance->getDescription().' ,  Calibration Performance';
    }
}


echo (new CalibrationLines (new SmartBuildLines(new BuildLines())))->getDescription().'\n';

echo (new CalibrationLines (new SmartBuildLines(new BuildLines())))->getLineOutput().'\n';

echo (new CalibrationLines (new SmartBuildLines(new BuildLines())))->getTargetPerShift().'\n';

