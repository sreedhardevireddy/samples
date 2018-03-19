<?php
/**
 * Created by PhpStorm.
 * User: sreedhardevireddy
 * Date: 24/10/2018
 * Time: 11:10
 *
 * Design Pattern used: Composite
 *
 * Very Useful pattern in FRS Shipment Tool as the RND samples object and Grouped pallets delivery object
 * has got the similar behaviour.
 *
 * Example scenario in Shipments: If we take BG as one composite it is having multiple suppliers as components
 * and again most likely each of which may be a composite. And its ultimately to representing the hirachy of the entire
 * UK and EU customers.
 *
 *
 */


/**
 * Class ReadyToShip
 */

abstract class ReadyToShip

{
    /**
     * @param $previousPallet
     * @return mixed
     */

    abstract function getPalletInfo($previousPallet);

    /**
     * @return mixed
     */
    abstract function getPalletCount();

    /**
     * @param $new_count
     * @return mixed
     */

    abstract function setMeterCount($new_count);

    /**
     * @param $onePallet
     * @return mixed
     */

    abstract function addPallet($onePallet);

    /**
     * @param $onePallet
     * @return mixed
     */
    abstract function removePallet($onePallet);
}


/**
 * Class OnePallet
 * Used for all the GRD sample pallets or one off customer pallet for new product introduction
 */
class OnePallet extends ReadyToShip

{

    private $palletname;
    private $customer;

    /**
     * OnePallet constructor.
     * @param $palletname
     * @param $customer
     */

    function __construct($palletname, $customer)
    {
        $this->palletname = $palletname;
        $this->customer = $customer;
    }

    /**
     * @param $palletToGet
     * @return bool|mixed|string
     */

    function getPalletInfo($palletToGet)
    {
        if (1 == $palletToGet) {
            return $this->palletname." FOR ".$this->customer;
        } else {
            return FALSE;
        }
    }

    /**
     * @return int|mixed
     *
     * Defaults to 1
     */

    function getPalletCount()
    {
        return 1;
    }

    /**
     * @param $newCount
     * @return bool|mixed
     */

    function setMeterCount($newCount)
    {
        return FALSE;
    }

    /**
     * @param $onePallet
     * @return bool|mixed
     */

    function addPallet($onePallet)
    {
        return FALSE;
    }

    /**
     * @param $onePallet
     * @return bool|mixed
     */

    function removePallet($onePallet)
    {
        return FALSE;
    }
}

/**
 * Class BulkPallets
 * Useful for bulk orders for customers like BG and NLC SMETS2 Meters
 */

class BulkPallets extends ReadyToShip
{
    private $delivery = array();

    private $palletCount;

    /**
     * BulkPallets constructor.
     */

    public function __construct()
    {
        $this->setMeterCount(0);
    }

    /**
     * @return mixed
     */

    public function getPalletCount()
    {
        return $this->palletCount;
    }

    /**
     * @param $newCount
     * @return mixed|void
     */

    public function setMeterCount($newCount)
    {
        $this->palletCount = $newCount;
    }

    /**
     * @param $palletToGet
     * @return bool|mixed
     */

    public function getPalletInfo($palletToGet)
    {
        if ($palletToGet <= $this->palletCount)
        {
            return $this->delivery[$palletToGet]->getPalletInfo(1);

        } else {

            return FALSE;
        }
    }

    /**
     * @param $onePallet
     * @return mixed
     *
     * Call this method for the all the selected pallets in a specific delivery
     */

    public function addPallet($onePallet)
    {
        $this->setMeterCount($this->getPalletCount() + 1);

        $this->delivery[$this->getPalletCount()] = $onePallet;

        return $this->getPalletCount();
    }

    /**
     * @param $onePallet
     * @return mixed
     *
     * Call incase of unshipping the pallets
     */

    public function removePallet($onePallet)
    {
        $counter = 0;

        while (++$counter <= $this->getPalletCount()) {

            if ($onePallet->getPalletInfo(1) ==
                $this->delivery[$counter]->getPalletInfo(1))
            {
                for ($x = $counter; $x < $this->getPalletCount(); $x++)
                {
                    $this->delivery[$x] = $this->delivery[$x + 1];
                }
                $this->setMeterCount($this->getPalletCount() - 1);
            }
        }
        return $this->getPalletCount();
    }
}


writeln(" TESTING SHIPMENT WITH COMPOSITE PATTERN");
writeln('');

$firstPallet = new OnePallet(' RND001', 'RND');
writeln('(after creating first Pallet) onePallet info: ');
writeln($firstPallet->getPalletInfo(1));
writeln('');


$seconfPalletFromDelivery = new OnePallet('NL002', 'NLC');
writeln('(after creating second pallet) onePallet info: ');
writeln($seconfPalletFromDelivery->getPalletInfo(1));
writeln('');


$multiplePallets = new BulkPallets();

$palletCount = $multiplePallets->addPallet($firstPallet);
writeln('(after adding firstPallet to stores) Bulk info : ');
writeln($multiplePallets->getPalletInfo($palletCount));
writeln('');

$palletCount = $multiplePallets->addPallet($seconfPalletFromDelivery);
writeln('(after adding secondPallet to Stores) Bulk info : ');
writeln($multiplePallets->getPalletInfo($palletCount));
writeln('');

$palletCount = $multiplePallets->removePallet($firstPallet);
writeln('(after removing firstPallet from Delivery) Bulkpallets count : ');
writeln($multiplePallets->getPalletCount());
writeln('');


function writeln($line_in) {
    echo $line_in.PHP_EOL;
}
