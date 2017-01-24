<?php
/**
 * Created by PhpStorm.
 * User: basecom
 * Date: 14.12.16
 * Time: 17:17
 */

use Shopware\Components\CSRFWhitelistAware;
/**
 * Class Shopware_Controllers_Api_Klip
 *
 */
class Shopware_Controllers_Frontend_Klip extends Enlight_Controller_Action implements CSRFWhitelistAware
{

    public function getWhitelistedCSRFActions()
    {
        return [
            'index'
        ];
    }

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

        //alle Infos auf einmal
        if ($requestedData==null)
        {
            //Verkäufe
            $result['anzahlBestellungenLetzte30TageProTag'] = $this->getSalesMonthPerDay('30', '0');
            $result['anzahlBestellungenLetzte30TageGesamt']=$this->getSalesPerMonth('30','0');
            $result['anzahlBestellungenLetzte30-60TageProTag'] = $this->getSalesMonthPerDay('60', '0');
            $result['anzahlBestellungenLetzte30-60TageGesamt']=$this->getSalesPerMonth('60', '30');
            $result['anzahlBestellungenHeute'] = $this->getSalesPerMonth('1', '0');
            //Umsätze

            //Durchschnitt
            $result['warenkorbWertDurchschnittLetzte30Tage'] = $this->getOrderbasketAverageDayMonth('30', '0');
            $result['warenkorbWertDurchschnittLetzte60-30Tage'] = $this->getOrderbasketAverageDayMonth('60', '30');
            $result['umsatzProTagDurchschnittNetto']=$this->getTurnoverperDay();

            //Entwicklung
            $result['umsatzLetzte30Tage'] = $this->getTurnoverPerMonth();
            $result['umsatzProJahr']=$this->getTurnoverPerYear();

            //andere Kriterien
            $result['umsatzProKundeImJahr']=$this->getTurnoverClientYear();
            $result['durchschnittsumsatzProKunde']= $this->getTurnoverPerCustomer();
            $result['umsatzProVersandart']=$this->getTurnoverPerShipment();
            $result['umsatzProZahlart']=$this->getTurnoverPerPayment();
            $result['umsatzGesamt'] = $this->getTurnover();

            //Bestellungen
            $result['durchschnittswertBestellungen']= $this->getAverageOrderValue();
            $result['durchschnittlicheAnzahlBestellungenProKunde']=$this->getOrderPerCustomer();
            $result['anzahlBestellungen']=$this->getNumberOrders();
            $result['anzahlBestellungenInBearbeitung']=$this->getNumberOrdersProcess();

            //Artikel
            $result['verkaufteStückzahlProTag']=$this->getSoldPiecesDay();
            $result['durchschnittlichVerkaufteStückzahlProTag']=$this->getSoldPiecesDayAverage();
            $result['durchschnittspreisVerkaufterArtikel']= $this->getAveragePriceSoldArticle();

            //Kunden
            $result['anzahlNeuerKunden']=$this->getNumberNewClients();
            $result['anzahlBestandskunden']=$this->getNumberReturnedClients();

            //TopTen
            $result['topTenArtikelNachUmsatz']=$this->getTopTenArticleTurnover();
            $result['topTenArtikelVerkaufteStückzahl']=$this->getTopTenArticlePiece();
            $result['topTenKundenNachUmsatz']=$this->getTopTenClientTurnover();



        }
        else {
            switch (strtolower($requestedData)) {



                //Bestellungen

                    //Anzahl Bestellungen der letzten 0-30 Tage</
                    case 'salesthismonthperday':
                        $result = $this->getSalesMonthPerDay('30', '0');
                        break;

                    //Anzahl der letzten 30 Tage insgesammt </
                    case 'salesthismonth':
                        $result=$this->getSalesPerMonth('30', '0');
                        break;

                    //Anzahl Bestellungen der letzten 30-60 Tage </
                    case 'saleslastmonthperday':
                        $result = $this->getSalesMonthPerDay('60', '30');
                        break;

                    //Anzahl der letzten 30-60 Tage insgesammt </
                    case 'saleslastmonth':
                        $result=$this->getSalesPerMonth('60', '30');
                        break;

                    //Anzahl Bestellungen heute </
                    case 'salestoday':
                        $result = $this->getSalesPerMonth('1', '0');
                        break;

                //-------------------------------------------------------------------------------------------------

                //Umsatz


                    //Durchschnittsumsatz

                        //Warenkorbwert der letzten 30 Tage durchschnitt </
                        case 'orderbasketaveragepermonth':
                            $result = $this->getOrderbasketAverageDayMonth('30', '0');
                            break;

                        //Warenkorbwert der letzten 30-60 Tage </
                        case 'orderbasketaveragelastmonth':
                            $result = $this->getOrderbasketAverageDayMonth('60', '30');
                            break;

                        //durchschnittl. Umsatz pro Tag Netto </
                        case 'turnoverperdaynetto':
                            $result=$this->getTurnoverperDay();
                            break;

                //---------------------------------------------------------------------------------

                    //Umsatzentwicklungen

                        //Umsatzentwicklung je Tag der letzten 30 Tage als Kurve (Liste der Umsätze je Tag) </
                        case 'turnoverpermonth':
                            $result = $this->getTurnoverPerMonth();
                            break;

                        //Umsatz pro Jahr </
                        case 'turnoverperyear':
                            $result=$this->getTurnoverPerYear();
                            break;

                //----------------------------------------------------------------------------

                    //Umsatz nach anderen Kriterien

                        //Umsatz pro Kunde im Jahr </
                        case 'turnoverperclientyear':
                            $result=$this->getTurnoverClientYear();
                            break;

                        //Durchschnittsumsatz pro Kunde </
                        case 'turnoverpercustomer':
                            $result= $this->getTurnoverPerCustomer();
                            break;

                        //Umsatz pro Versandart </
                        case 'turnoverpershipment':
                            $result=$this->getTurnoverPerShipment();
                            break;

                        //Umsatz pro Zahlart </
                        case 'turnoverperpayment':
                            $result=$this->getTurnoverPerPayment();
                            break;

                    //Umsatz Gesamt </
                        case 'turnoveralltime':
                            $result = $this->getTurnover();
                            break;


                //-----------------------------------------------------------------------

                //Bestellungen

                    //Durchschnitt

                        //Durchschnittswert der Bestellungen </
                        case 'averageordervalue':
                            $result= $this->getAverageOrderValue();
                            break;

                        //Durchschnittliche Anzahl Bestellungen pro Kunde
                        case 'averagenumberorderpercustomer':
                            $result= $this->getOrderPerCustomer();
                            break;

                    //Anzahl

                        //Anzahl der Bestellungen </
                        case 'numberorders':
                            $result=$this->getNumberOrders();
                            break;

                        //Anzahl Bestellungen in Bearbeitung </
                        case 'numberordersprocess':
                            $result=$this->getNumberOrdersProcess();
                            break;


                //--------------------------------------------------------------------------------

                //Artikel

                    //verkaufte Stückzahl pro Tag </
                    case 'soldpiecesday':
                        $result=$this->getSoldPiecesDay();
                        break;


                    //durchschnittl. verkaufte Stückzahl pro Tag </
                    case 'averagesoldpiecesday':
                        $result=$this->getSoldPiecesDayAverage();
                        break;


                    //Durchschnittspreis verkaufter Artikel </
                    case 'averagepricesoldarticle':
                        $result= $this->getAveragePriceSoldArticle();
                        break;

                //-------------------------------------------------------------------

                //Kunden

                    //Anzahl neuer Kunden </
                    case 'numbernewclients':
                        $result=$this->getNumberNewClients();
                        break;

                    //Anzahl Bestandskunden </
                    case 'numberreturnedclients':
                        $result=$this->getNumberReturnedClients();
                        break;

                //---------------------------------------------------------------------

                //Top Ten

                    //Top Ten Artikel Umsatz
                    case 'toptenarticleturnover':
                        $result=$this->getTopTenArticleTurnover();
                        break;

                    //Top Ten Artikel pro Stück
                    case 'toptenarticlepiece':
                        $result=$this->getTopTenArticlePiece();
                        break;

                    //Top Ten Kunden Umsatz
                    case 'toptencustomerturnover':
                        $result=$this->getTopTenClientTurnover();
                        break;
                //---------------------------------------------------------------------

            }
        }


        //Ergebnis in gewünschtes Format
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

    //Verkäufe

    //Verkäufe pro Tag
    public function getSalesMonthPerDay($from, $to)
    {
        $now=new DateTime();
        $then=new DateTime();
        $now->modify('-'.$to.' days');
        $then->modify('-'.$from.' days');

        //Alle Aufträge
        $sql='SELECT COUNT(*) as ordercount, CONVERT(ordertime, DATE) as date
        FROM s_order
        WHERE ordertime<"'.$now->format('Y-m-d H:i:s').'" AND ordertime>"'.$then->format('Y-m-d H:i:s').'"
        GROUP BY CONVERT(ordertime,DATE)
        ORDER BY ordertime';
        $sales= $this->query($sql);

        //gecancellte Aufträge
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

    //Verkäufe im gewählten Zeitraum insgesammt
    public function getSalesPerMonth($from, $to)
    {
        //Tägliche Anzahl an Verkäufen im Zeitraum von einem Monat
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

    //Umsatz/ Warenkorb im Monat

    //Warenkorbwert Durchschnitt pro Tag in einem Monat
    public function getOrderbasketAverageDayMonth($startdate, $enddate)
    {
        $then=new DateTime();
        $now=new DateTime();
        $then=$then->modify('-'.$startdate.' days')->format('Y-m-d H:i:s');
        $now=$now->modify('-'.$enddate.' days')->format('Y-m-d H:i:s');

        //Differenz aus summiertem Preis der Artikel und anzahl der unterschiedlichen Sessions in den letzten 30 Tagen
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

    //durchschnittl.  Umsatz pro Tag Netto
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

    //Umsatzentwicklung

    //Umsatz im Monat
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

    //Umsatz pro Jahr
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

    //Umsatz nach anderen Kriterien

    //Durchschnittskundenumsatz pro Jahr
    public function getTurnoverClientYear()
    {
        $orders=$this->getTurnoverPerYear()['turnoverPerYear'];

        //Anzahl der Kunden abhängig vom Jahr des Beitritts
        $sql='SELECT COUNT(id) AS Number, YEAR(firstlogin) AS Year
        FROM s_user
        GROUP BY YEAR(firstlogin)
        ORDER BY firstlogin';
        $users=$this->query($sql);
        $turnover=array();

        $p=0;
        $usersall=0;

        //Durchgehen der Jahre ausgehend vom ersten Jahr mit Kunde
        for($i=$users[0]['Year']; $i<=$orders[count($orders)-1]['Year']; $i++) {
            $userdate=$i;

            //Anzahl der neuen Kunden mit denen vom Vorjar, die ja erhalten sind
            for($j=0; $j<count($users); $j++) {
                if($users[$j]['Year']==$userdate) {
                    $usersall+=$users[$j]['Number'];
                    break;
                }
            }

            //Aufträge durchgehen und durch anzahl der Kunden bis zu diesem Jahr teilen
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

    //Durchschnittsumsatz Kunden
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

    //Umsatz pro Versandart
    public function getTurnoverPerShipment()
    {
        //Umsatz nach Versandart
        $sql = '
        SELECT
          SUM(invoice_amount) AS invoice, dispatchID
        FROM
          s_order AS orders
        GROUP BY orders.dispatchID';

        $invoice=$this->query($sql);

        //Name der Versandart
        for($i=0; $i<count($invoice); $i++) {
            $sql='SELECT name
            FROM s_premium_dispatch
            WHERE id="'.$invoice[$i]['dispatchID'].'"';
            $name=$this->query($sql);
            $invoice[$i]['name']=$name[0]['name'];
        }

        $result['turnoverpershippment']=$invoice;
        return $result;
    }

    //Umsatz pro Zahlart
    public function getTurnoverPerPayment()
    {
        //Umsatz nach Zahlart
        $sql = '
        SELECT
          SUM(invoice_amount) AS invoice, paymentID
        FROM
          s_order AS orders
        GROUP BY orders.paymentID';
        $invoice=$this->query($sql);

        //Name der Zahlart
        for($i=0; $i<count($invoice); $i++) {
            $sql='SELECT name
            FROM s_core_paymentmeans
            WHERE id="'.$invoice[$i]['paymentID'].'"';
            $name=$this->query($sql);
            $invoice[$i]['name']=$name[0]['name'];
        }

        $result['turnoverperpayment']=$invoice;
        return $result;
    }


    //Suche Umsatz insgesammt
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


    //Bestellungen

    //Durchschnittswert der Bestellungen
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

    //Durchschnittliche Anzahl Bestellungen pro Kunde
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

    //Anzahl Bestellungen
    public function getNumberOrders()
    {
        //Alle benutzten Statuse und Anzahl der Bestellungen im Status
        $sql='
        SELECT
            COUNT(status) AS number, status
        FROM
          s_order
        GROUP BY status';
        $orders=$this->query($sql);
        $all=0;

        //Namen des Status
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

    //Anzahl Bestellungen 'in Arbeit'
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

    //Artikel

    //Verkaufte Stückzahl pro Tag !!!!!!!!!!!!!!!!!!!!!!!
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

    //durchschnittl. verkaufte Stückzahl pro Tag
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



    //Durchschnittspreis verkaufter Artikel
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

    //Kunden

    //Anzahl neuer Kunden
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

    //Anzahl Bestandskunden
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

    //Top Tens


    //Top Ten Artikel Umsatz
    public function getTopTenArticleTurnover()
    {
        //errechne Umsatz aus Preis und Anzahl Verkäufe, Ordne nach Umsatz, gib erste 10 aus mit Name, id, Umsatz
        $sql='SELECT DISTINCT price.articleID, price.price * sales.sales AS turnover, article.name
        FROM s_articles_top_seller_ro AS sales, s_articles_prices AS price, s_articles AS article
        WHERE price.articleID=sales.article_id AND price.articleID=article.id
        ORDER BY turnover DESC
        LIMIT 10';
        $articles=$this->query($sql);

        $result['TopTenArticleTurnover']=$articles;
        return $result;
    }

    //Top Ten Artikel Stück
    public function getTopTenArticlePiece()
    {

        //Anzahl an Verkäufen pro Artikel
        $sql='SELECT
        sales, article_id
        FROM s_articles_top_seller_ro
        ORDER BY sales DESC
        LIMIT 10';

        $articles=$this->query($sql);

        for($i=0; $i<count($articles);$i++)
        {
            //Name der Artikel
            $sql='SELECT name FROM s_articles WHERE id="'.$articles[$i]['article_id'].'"';
            $name=$this->query($sql);
            $articles[$i]['name']=$name[0]['name'];
        }

        $result['TopTenArticlePiece']=$articles;
        return $result;
    }


    //Top Ten Kunden Umsatz
    public function getTopTenClientTurnover()
    {
        //Umsatz pro Kunden
        $sql='
        SELECT
            userID, SUM(orders.invoice_amount / orders.currencyFactor) as turnover
        FROM
            s_order AS orders
        GROUP BY userID
        ORDER BY turnover DESC
        LIMIT 10';
        $turnover=$this->query($sql);

        //Name des Kunden
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

    //Hilfsfunktionen

    //Datum in das richtige Format setzen
    public function getDate($timeFromNow, $format)
    {
        $Date = $this->Request()->getParam('startdate', date("Y-m-d"));
        $Date=DateTime::createFromFormat('Y-m-d', $Date);
        $Date->getTimestamp();

        $Date->sub(date_interval_create_from_date_string($timeFromNow.' days'));
        $Date=$Date->format($format);
        return $Date;
    }

    //Datum in Format setzen
    public function getDateFormat($date, $format, $finale)
    {
        $date=DateTime::createFromFormat($format, $date);
        $date->getTimestamp();
        $date=$date->format($finale);
        return $date;
    }

    //Datenbank durchsuchen
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