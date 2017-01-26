<?php

use Shopware\Components\CSRFWhitelistAware;
/**
 * Class Shopware_Controllers_Api_Klip
 * @author Anna Sophia Sommer <sommer@basecom.de>
 */
class Shopware_Controllers_Frontend_Klip extends Enlight_Controller_Action implements CSRFWhitelistAware
{

    /**
     * @return array
     */
    public function getWhitelistedCSRFActions()
    {
        return [
            'index'
        ];
    }

    /**
     * @return string
     */
    private function getRequestedFormat()
    {
        return (string) $this->Request()->getParam('output');
    }

    private function getRequestedData()
    {
        return (string) $this->Request()->getParam('data');
    }

    /**
     * GET Request on /api/KlipSalesPerMonth
     */
    public function indexAction()
    {

        $result=null;
        $requestedData=$this->getRequestedData();

        //get all available content
        if ($requestedData==null)
        {
            //sales
            $result['salesThisMonthPerDay'] = $this->getSalesMonthPerDay('30', '0');
            $result['salesThisMonthOverall']=$this->getSalesPerMonth('30','0');
            $result['salesLastMonthPerDay'] = $this->getSalesMonthPerDay('60', '0');
            $result['salesLastMonthOverall']=$this->getSalesPerMonth('60', '30');
            $result['salesToday'] = $this->getSalesPerMonth('1', '0');

            //turnovers

            //average
            $result['orderBasketAverageThisMonth'] = $this->getOrderbasketAverageDayMonth('30', '0');
            $result['orderBasketAverageLastMonth'] = $this->getOrderbasketAverageDayMonth('60', '30');
            $result['turnoverAveragePerDayNetto']=$this->getTurnoverperDay();

            //development
            $result['turnoverThisMonth'] = $this->getTurnoverPerMonth();
            $result['turnoverPerYear']=$this->getTurnoverPerYear();

            //other criteria
            $result['turnoverPerClientYear']=$this->getTurnoverClientYear();
            $result['turnoverAveragePerCustomer']= $this->getTurnoverPerCustomer();
            $result['turnoverPerShipment']=$this->getTurnoverPerShipment();
            $result['turnoverPerPayment']=$this->getTurnoverPerPayment();
            $result['turnoverOverall'] = $this->getTurnover();

            //orders
            $result['averageNumberOrders']= $this->getAverageOrderValue();
            $result['averageNumberOrdersPerCustome']=$this->getOrderPerCustomer();
            $result['numberOrders']=$this->getNumberOrders();
            $result['numberOrdersInProcess']=$this->getNumberOrdersProcess();

            //articles
            $result['soldPiecesPerDay']=$this->getSoldPiecesDay();
            $result['averageSoldPiecesPerDay']=$this->getSoldPiecesDayAverage();
            $result['averagePriceSoldPiecesl']= $this->getAveragePriceSoldArticle();

            //customers
            $result['numberNewCustomers']=$this->getNumberNewClients();
            $result['numberReturnedCustomers']=$this->getNumberReturnedClients();

            //TopTen
            $result['topTenArticleTurnover']=$this->getTopTenArticleTurnover();
            $result['topTenArticleSoldPieces']=$this->getTopTenArticlePiece();
            $result['topTenCustomersTurnover']=$this->getTopTenClientTurnover();



        }
        else {
            switch (strtolower($requestedData)) {



                //orders

                    //quantity of orders in the last 30 days per day
                    case 'salesthismonthperday':
                        $result = $this->getSalesMonthPerDay('30', '0');
                        break;

                    //quantity of orders in the last 30 days overall
                    case 'salesthismonth':
                        $result=$this->getSalesPerMonth('30', '0');
                        break;

                    //quantity of sales in the last 30-60 days per day
                    case 'saleslastmonthperday':
                        $result = $this->getSalesMonthPerDay('60', '30');
                        break;

                    //quantity of sales in the last 30 - 60 days overall
                    case 'saleslastmonth':
                        $result=$this->getSalesPerMonth('60', '30');
                        break;

                    //quantity orders today
                    case 'salestoday':
                        $result = $this->getSalesPerMonth('1', '0');
                        break;

                //-------------------------------------------------------------------------------------------------

                //turnover


                    //average

                        //order basket value average last 30 days
                        case 'orderbasketaveragepermonth':
                            $result = $this->getOrderbasketAverageDayMonth('30', '0');
                            break;

                        //order basket value average last 30-60 days
                        case 'orderbasketaveragelastmonth':
                            $result = $this->getOrderbasketAverageDayMonth('60', '30');
                            break;

                        //turnover average per day netto
                        case 'turnoverperdaynetto':
                            $result=$this->getTurnoverperDay();
                            break;

                //---------------------------------------------------------------------------------

                    //turnover development

                        //turnoverdevelopment per day last 30 days
                        case 'turnoverpermonth':
                            $result = $this->getTurnoverPerMonth();
                            break;

                        //turnover per yeas
                        case 'turnoverperyear':
                            $result=$this->getTurnoverPerYear();
                            break;

                //----------------------------------------------------------------------------

                    //turnover other criterias

                        //turnover per client per year
                        case 'turnoverperclientyear':
                            $result=$this->getTurnoverClientYear();
                            break;

                        //turnover average per customer
                        case 'turnoverpercustomer':
                            $result= $this->getTurnoverPerCustomer();
                            break;

                        //turnover per shippment
                        case 'turnoverpershipment':
                            $result=$this->getTurnoverPerShipment();
                            break;

                        //turnover per payment
                        case 'turnoverperpayment':
                            $result=$this->getTurnoverPerPayment();
                            break;

                        //turnover all time
                        case 'turnoveralltime':
                            $result = $this->getTurnover();
                            break;


                //-----------------------------------------------------------------------

                //orders

                    //average

                        //average order value
                        case 'averageordervalue':
                            $result= $this->getAverageOrderValue();
                            break;

                        //average quantity orders per customer
                        case 'averagenumberorderpercustomer':
                            $result= $this->getOrderPerCustomer();
                            break;

                    //quantity

                        //quantity orders per status
                        case 'numberorders':
                            $result=$this->getNumberOrders();
                            break;

                        //quantity orders 'in process'
                        case 'numberordersprocess':
                            $result=$this->getNumberOrdersProcess();
                            break;


                //--------------------------------------------------------------------------------

                //articles

                    //sold articles per day
                    case 'soldpiecesday':
                        $result=$this->getSoldPiecesDay();
                        break;


                    //average sold articles per day
                    case 'averagesoldpiecesday':
                        $result=$this->getSoldPiecesDayAverage();
                        break;


                    //average price sold articles
                    case 'averagepricesoldarticle':
                        $result= $this->getAveragePriceSoldArticle();
                        break;

                //-------------------------------------------------------------------

                //customers

                    //quantity new customers
                    case 'numbernewclients':
                        $result=$this->getNumberNewClients();
                        break;

                    //quantity returned customers
                    case 'numberreturnedclients':
                        $result=$this->getNumberReturnedClients();
                        break;

                //---------------------------------------------------------------------

                //Top Ten

                    //Top Ten Article per turnover
                    case 'toptenarticleturnover':
                        $result=$this->getTopTenArticleTurnover();
                        break;

                    //Top Ten Article per piece
                    case 'toptenarticlepiece':
                        $result=$this->getTopTenArticlePiece();
                        break;

                    //Top Ten customers per turnover
                    case 'toptencustomerturnover':
                        $result=$this->getTopTenClientTurnover();
                        break;
                //---------------------------------------------------------------------

            }
        }


        //result in given format or (if no format given) in json
        $format=$this->getRequestedFormat();
        if($format==null)
            $format='json';

        $service=$this->container->get('anna_klip_api.data_transformer');
        $service->__construct($format, $result);
        $result=$service->getTransformedData();

        echo $result;

        exit();

    }

    //-----------------------------------------------------------------------

    //sales

    //sales per day
    /**
     * @param $from
     * @param $to
     * @return mixed
     */
    public function getSalesMonthPerDay($from, $to)
    {
        $now=new DateTime();
        $then=new DateTime();
        $now->modify('-'.$to.' days');
        $then->modify('-'.$from.' days');

        //all orders
        $sql='SELECT COUNT(*) as ordercount, CONVERT(ordertime, DATE) as date
        FROM s_order
        WHERE ordertime<"'.$now->format('Y-m-d H:i:s').'" AND ordertime>"'.$then->format('Y-m-d H:i:s').'"
        GROUP BY CONVERT(ordertime,DATE)
        ORDER BY ordertime';
        $sales= $this->query($sql);

        //cancelled orders
        $sql='SELECT COUNT(*) as ordercount, CONVERT(ordertime, DATE) as date
        FROM s_order
        WHERE status=-1 AND ordertime<"'.$now->format('Y-m-d H:i:s').'" AND ordertime>"'.$then->format('Y-m-d H:i:s').'"
        GROUP BY CONVERT(ordertime, DATE)
        ORDER BY ordertime';
        $salesCancelled=$this->query($sql);
        $p=0;
        for($i=0; $i<count($sales); $i++)
        {
            if($sales[$i]['date']==$salesCancelled[$p]['date'])
            {
                $sales[$i]['ordersCancelled']=$salesCancelled[$p]['ordercount'];
                $p++;
            }
        }

        $result['salesPerMonthPerDay']=$sales;
        return $result;
    }

    //sales in given time overall
    /**
     * @param $from
     * @param $to
     * @return mixed
     */
    public function getSalesPerMonth($from, $to)
    {
        //daily quantity of orders in given time
        $now=new DateTime();
        $then=new DateTime();
        $now->modify('-'.$to.' days');
        $then->modify('-'.$from.' days');

        $sql='SELECT COUNT(*) AS orders
        FROM s_order 
        WHERE ordertime<"'.$now->format('Y-m-d H:i:s').'" 
        AND ordertime>"'.$then->format('Y-m-d H:i:s').'"';
        $sales=$this->query($sql);

        $sql='SELECT COUNT(*) AS orders
        FROM s_order 
        WHERE ordertime<"'.$now->format('Y-m-d H:i:s').'" AND status=-1
        AND ordertime>"'.$then->format('Y-m-d H:i:s').'"';

        $cancelledOrders=$this->query($sql);

        $sales[0]['cancelledOrders']=$cancelledOrders[0]['orders'];
        $date=''.$then->format('Y-m-d').' - '.$now->format('Y-m-d').'';
        $sales[0]['Date']=$date;

        $result['salesAverage']=$sales;
        return $result;
    }
    //--------------------------------------------------------------------------------------------------

    //turnover/ order basket

    //average order basket value per day per month
    /**
     * @param $startdate
     * @param $enddate
     * @return mixed
     */
    public function getOrderbasketAverageDayMonth($startdate, $enddate)
    {
        $then=new DateTime();
        $now=new DateTime();
        $then=$then->modify('-'.$startdate.' days')->format('Y-m-d H:i:s');
        $now=$now->modify('-'.$enddate.' days')->format('Y-m-d H:i:s');

        //difference from sum of the articles an number of different sessions in the given time
        $sql='
        SELECT SUM(price)/COUNT(DISTINCT sessionID)
        FROM
          s_order_basket
        WHERE
          datum>="'.$then.'" AND datum<="'.$now.'"';
        $orderBasket=$this->query($sql)[0];

        $result['orderBasket']=$orderBasket['SUM(price)/COUNT(DISTINCT sessionID)'];

        return $result;
    }

    //average turnover per day netto
    /**
     * @return mixed
     */
    public function getTurnoverperDay()
    {
        $Date=$this->getDate('30', 'Y-m-d H:i:s');

        $sql='
        SELECT
            SUM(invoice_amount_net)/30
        FROM
          s_order
        WHERE
          ordertime>"'.$Date.'"';

        $orders=$this->query($sql);

        $result['turnoverPerDayNetto']=$orders[0]['SUM(invoice_amount_net)/30'];
        return $result;
    }

    //-------------

    //development turnover

    //turnover per month
    /**
     * @return mixed
     */
    public function getTurnoverPerMonth()
    {
        $then=new DateTime();
        $now=new DateTime();
        $then=$then->modify('-30 days')->format('Y-m-d H:i:s');
        $now=$now->format('Y-m-d');

        $sql='SELECT SUM(invoice_amount) AS turnover, CONVERT(ordertime, DATE) AS ordertime
        FROM s_order
        WHERE ordertime<"'.$now.'"AND ordertime>"'.$then.'"
        GROUP BY CONVERT(ordertime, DATE)
        ORDER BY CONVERT(ordertime, DATE)';

        $orders=$this->query($sql);
        $result['turnoverThisMonth']=$orders;
        return $result;
    }

    //turnover per year
    /**
     * @return mixed
     */
    public function getTurnoverPerYear()
    {
        $sql='SELECT
        YEAR(ordertime) AS Year, SUM(invoice_amount) AS Turnover
        FROM s_order
        GROUP BY YEAR(ordertime)
        ORDER BY ordertime';

        $invoice=$this->query($sql);

        $result['turnoverPerYear']=$invoice;
        return $result;
    }

    //-----------------

    //turnover other criterias

    //average turnover per customer per year
    /**
     * @return mixed
     */
    public function getTurnoverClientYear()
    {
        $orders=$this->getTurnoverPerYear()['turnoverPerYear'];

       //quantity of customers depending on their first log in
        $sql='SELECT COUNT(id) AS Number, YEAR(firstlogin) AS Year
        FROM s_user
        GROUP BY YEAR(firstlogin)
        ORDER BY firstlogin';
        $users=$this->query($sql);
        $turnover=array();

        $p=0;
        $usersall=0;

        //Loop through the years beginning with first customer
        for($i=$users[0]['Year']; $i<=$orders[count($orders)-1]['Year']; $i++) {
            $userdate=$i;

            //add new customers this year to customers last year
            for($j=0; $j<count($users); $j++) {
                if($users[$j]['Year']==$userdate) {
                    $usersall+=$users[$j]['Number'];
                    break;
                }
            }

            //Aufträge durchgehen und durch anzahl der Kunden bis zu diesem Jahr teilen
            //loop through orders and devide through customers this year
            for($j=0; $j<count($orders); $j++) {
                if($userdate==$orders[$j]['Year']) {
                    $turnover[$p]['Year']=$userdate;
                    $turnover[$p]['Turnover']=$orders[$j]['Turnover']/$usersall;
                    $p++;
                    $j=count($orders);
                }
            }
        }
        $result['turnoverPerYearPerClient']=$turnover;
        return $result;
    }

    //average turnover per customer
    /**
     * @return mixed
     */
    public function getTurnoverPerCustomer()
    {
        $turnover=$this->getTurnover()['turnoverAllTime'];

        $sql='
        SELECT
            count(users.id)
        FROM
          s_user AS users';
        $users=$this->query($sql)[0]['count(users.id)'];

        $result['turnoverPerCustomer']=$turnover/$users;
        return $result;
    }

    //turnover per shippment
    /**
     * @return mixed
     */
    public function getTurnoverPerShipment()
    {
        //turnover per shippment
        $sql = '
        SELECT
          SUM(invoice_amount) AS invoice, dispatchID
        FROM
          s_order AS orders
        GROUP BY orders.dispatchID';

        $invoice=$this->query($sql);

        //name of shippment
        for($i=0; $i<count($invoice); $i++) {
            $sql='SELECT name
            FROM s_premium_dispatch
            WHERE id="'.$invoice[$i]['dispatchID'].'"';
            $name=$this->query($sql);
            $invoice[$i]['name']=$name[0]['name'];
        }

        $result['turnoverPerShippment']=$invoice;
        return $result;
    }

    //turnover per payment
    /**
     * @return mixed
     */
    public function getTurnoverPerPayment()
    {
        //turnover per payment
        $sql = '
        SELECT
          SUM(invoice_amount) AS invoice, paymentID
        FROM
          s_order AS orders
        GROUP BY orders.paymentID';
        $invoice=$this->query($sql);

        //name of payment
        for($i=0; $i<count($invoice); $i++) {
            $sql='SELECT name
            FROM s_core_paymentmeans
            WHERE id="'.$invoice[$i]['paymentID'].'"';
            $name=$this->query($sql);
            $invoice[$i]['name']=$name[0]['name'];
        }

        $result['turnoverPerPayment']=$invoice;
        return $result;
    }


    //turnover all time
    /**
     * @return mixed
     */
    public function getTurnover()
    {
        $sql="
        SELECT
            SUM(orders.invoice_amount / orders.currencyFactor) as turnover 
        FROM
            s_order AS orders
            WHERE
              (orders.status) NOT IN (-1, 4)
            
        ";

        $orders=$this->query($sql);
        $result['turnoverAllTime']=$orders[0]['turnover'];

        return $result;

    }

    //--------------------------------------------------------------------------------------------------


    //orders

    //average order value
    /**
     * @return mixed
     */
    public function getAverageOrderValue()
    {
        $sql="
        SELECT 
          AVG(invoice_amount)
        FROM 
          s_order";

        $amount=$this->query($sql)[0]['AVG(invoice_amount)'];

        $result['averageOrderValue']=$amount;
        return $result;
    }

    //average quantity of orders per customer
    /**
     * @return mixed
     */
    public function getOrderPerCustomer()
    {
        $sql='
        SELECT 
          COUNT(DISTINCT orders.id)/ COUNT(DISTINCT users.id) 
        FROM 
          s_user AS users,
          s_order AS orders';

        $query=$this->query($sql)[0]['COUNT(DISTINCT orders.id)/ COUNT(DISTINCT users.id)'];

        $result['averageNumberOrdersPerCustomer']=$query;
        return $result;
    }

    //quantity of orders per state
    /**
     * @return mixed
     */
    public function getNumberOrders()
    {
        //all used states and quantity of orders in this state
        $sql='
        SELECT
            COUNT(status) AS number, status
        FROM
          s_order
        GROUP BY status';
        $orders=$this->query($sql);
        $all=0;

        //name of the state
        for($i=0; $i<count($orders); $i++) {
            $number=$orders[$i]['number'];
            $all+=$number;
            $id=$orders[$i]['status'];

            $sql='SELECT name
            FROM s_core_states
            WHERE id="'.$id.'"';

            $status=$this->query($sql)[0]['name'];
            $orders[$i]['name']=$status;

        }
        $sql='SELECT COUNT(*)
        FROM s_order';
        $all=$this->query($sql);
        $orders[$i]['number']=$all[0]['COUNT(*)'];
        $orders[$i]['status']='-2';
        $orders[$i]['name']='all';

        $result['NumberOrders']=$orders;
        return $result;
    }

    //quantity of orders 'in process'
    /**
     * @return mixed
     */
    public function getNumberOrdersProcess()
    {
        $sql='
        SELECT
            orders.id
        FROM
          s_order AS orders
        WHERE
          orders.status=1';
        $orders=$this->query($sql);

        for($i=0; $i<count($orders); $i++);
        $result['NumberOrdersProcess']=$i;
        return $result;

    }

    //--------------------------------------------------------------------------

    //Articles

    //sold articles per day
    /**
     * @return mixed
     */
    public function getSoldPiecesDay()
    {
        $Date=$this->getDate('30', 'Y-m-d H:i:s');
        $sql='
        SELECT 
            count(id) AS Number, CONVERT(ordertime,DATE) AS Day
        FROM
          s_order
        WHERE
          ordertime>"'.$Date.'"
        GROUP BY CONVERT(ordertime, DATE)
        ORDER BY ordertime';

        $orders=$this->query($sql);

        $result['soldPiecesDay']=$orders;

        return $result;
    }

    //average sold articles per day
    /**
     * @return mixed
     */
    public function getSoldPiecesDayAverage()
    {
        $soldpieces=$this->getSoldPiecesDay();

        $soldpiecesaverage=0;
        for($i=0; $i<count($soldpieces);$i++)
        {
            $soldpiecesaverage+=$soldpieces['soldPiecesDay'][$i]['Number'];
        }

        $soldpiecesaverage=$soldpiecesaverage/30;
        $result['averageSoldPiecesDay']=$soldpiecesaverage;
        return $result;
    }



    //average price sold articles
    /**
     * @return mixed
     */
    private function getAveragePriceSoldArticle()
    {
        $sql="
        SELECT
            AVG(orders.price)
        FROM
          s_order_details AS orders
        WHERE
          modus=0";

        $price=$this->query($sql);

        $result['AveragePriceSoldArticle']=$price[0]['AVG(orders.price)'];
        return $result;
    }
    //----------------------------------------------------------------------

    //customers

    //quantity new customers
    /**
     * @return mixed
     */
    public function getNumberNewClients()
    {
        $then=new DateTime();
        $then->modify('-60 days');
        $then=$then->format('Y-m-d');
        $sql='
        SELECT
            COUNT(users.firstlogin) AS Number
        FROM
          s_user AS users
          WHERE 
          CONVERT(users.firstlogin, DATE)>"'.$then.'"';

        $user=$this->query($sql);

        $result['NumberNewClients']=$user[0]['Number'];
        return $result;
    }

    //quantity returned customers
    /**
     * @return mixed
     */
    public function getNumberReturnedClients()
    {
        $sql='
        SELECT
            count(firstlogin) AS Number
        FROM
          s_user
        WHERE firstlogin != CONVERT(lastlogin, DATE)';
        $user=$this->query($sql);
        $result['NumberReturnedClients']=$user[0]['Number'];
        return $result;
    }

    //---------------------------------------------------------------------------------

    //Top Ten


    //Top Ten article turnover
    /**
     * @return mixed
     */
    public function getTopTenArticleTurnover()
    {
        //calculate turnover from price and quantity of times sold, order after turnover return first ten with name id and turnover
        $sql='SELECT DISTINCT price.articleID, price.price * sales.sales AS turnover, article.name
        FROM s_articles_top_seller_ro AS sales, s_articles_prices AS price, s_articles AS article
        WHERE price.articleID=sales.article_id AND price.articleID=article.id
        ORDER BY turnover DESC
        LIMIT 10';
        $articles=$this->query($sql);

        $result['TopTenArticleTurnover']=$articles;
        return $result;
    }

    //Top Ten articles sold pieces
    /**
     * @return mixed
     */
    public function getTopTenArticlePiece()
    {

        //quantity of times sold per article
        $sql='SELECT
        sales, article_id
        FROM s_articles_top_seller_ro
        ORDER BY sales DESC
        LIMIT 10';

        $articles=$this->query($sql);

        for($i=0; $i<count($articles);$i++)
        {
            //name of the article
            $sql='SELECT name FROM s_articles WHERE id="'.$articles[$i]['article_id'].'"';
            $name=$this->query($sql);
            $articles[$i]['name']=$name[0]['name'];
        }

        $result['TopTenArticlePiece']=$articles;
        return $result;
    }


    //Top Ten customers turnover
    /**
     * @return mixed
     */
    public function getTopTenClientTurnover()
    {
        //turnover per customer
        $sql='
        SELECT
            userID, SUM(orders.invoice_amount / orders.currencyFactor) as turnover
        FROM
            s_order AS orders
        GROUP BY userID
        ORDER BY turnover DESC
        LIMIT 10';
        $turnover=$this->query($sql);

        //name of customer
        for($i=0; $i<count($turnover); $i++)
        {
            $id=$turnover[$i]['userID'];
            $sql='SELECT CONCAT(lastname,", ", firstname) AS name
            FROM s_user
            WHERE id="'.$id.'"';
            $name=$this->query($sql);
            $turnover[$i]['Name']=$name[0]['name'];
        }

        $result2['topTenClient']=$turnover;
        return $result2;
    }

    //---------------------------------------------------------------------

    //useful functions

    //get date in right format
    /**
     * @param $timeFromNow
     * @param $format
     * @return DateTime|mixed|string
     */
    public function getDate($timeFromNow, $format)
    {
        $Date = $this->Request()->getParam('startdate', date("Y-m-d"));
        $Date=DateTime::createFromFormat('Y-m-d', $Date);
        $Date->getTimestamp();

        $Date->sub(date_interval_create_from_date_string($timeFromNow.' days'));
        $Date=$Date->format($format);
        return $Date;
    }

    //get date in format
    /**
     * @param $date
     * @param $format
     * @param $finale
     * @return DateTime|string
     */
    public function getDateFormat($date, $format, $finale)
    {
        $date=DateTime::createFromFormat($format, $date);
        $date->getTimestamp();
        $date=$date->format($finale);
        return $date;
    }

    //query the database
    /**
     * @param $sql
     * @param $startdate
     * @param $enddate
     * @return array
     */
    public function query($sql, $startdate, $enddate)
    {
        if ($startdate==null&&$enddate==null)
        {
            $stmt = Shopware()->Db()->query($sql);
        }
        else {

            $stmt = Shopware()->Db()->query($sql, array(
                'startDate' => $startdate,
                'endDate' => $enddate
            ));
        }
        $result=$stmt->fetchAll();
        return $result;
    }
}