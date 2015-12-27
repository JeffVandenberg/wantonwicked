<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 7/19/2015
 * Time: 1:26 AM
 */

$locationsUrl = 'http://locator.wizards.com/Service/LocationService.svc/GetLocations';
$eventTypes = array(
    'GT',
    'PPTQ',
    'RPTQ'
);
$salesBrandCode = array(//    'MG'
);
$productLineCodes = array(//    'MG'
);
$payload = array(
    'language' => "en-us",
    'request' => array(
        'North' => 47.43051911539,
        'East' => -122.15397811701,
        'South' => 46.98346908461,
        'West' => -122.81202808299,
        'LocalTime' => "/Date(" . Scrub::getDateForRequest() . ")/",
        'ProductLineCodes' => $productLineCodes,
        'EventTypeCodes' => $eventTypes,
        'PlayFormatCodes' => array(),
        'SalesBrandCodes' => $salesBrandCode,
        'MarketingProgramCodes' => array(),
        'EarliestEventStartDate' => null,
        'LatestEventStartDate' => null
    ),
    'page' => 1,
    'count' => 30,
    'filter_mass_markets' => true,
);

?>
    <h3>
        Events for <?php echo implode(', ', $eventTypes); ?>
    </h3>
<?php
$stores = Scrub::performRequest($locationsUrl, $payload);
if ($stores) {
    foreach ($stores['d']['Results'] as $store) {
        ?>
        -------------------------------------------------<br />
        Store: <?php echo $store['Organization']['Name']; ?><br/>
        Id: <?php echo $store['Organization']['Id']; ?><br/>
        Phone: <input type="text" value="<?php echo $store['Organization']['Phone']; ?>" /><br/>
        Email: <input type="text" value="<?php echo $store['Organization']['Email']; ?>" /><br/>
        URL: <input type="text" value="<?php echo $store['Organization']['PrimaryUrl']; ?>" /><br/>
        Address:
<textarea style="width:300px;height:80px;">
<?php echo $store['Address']['Line1'] . "\n"; ?>
<?php if ($store['Address']['Line2']): ?><?php echo $store['Address']['Line2'] . "\n"; ?><?php endif; ?>
<?php if ($store['Address']['Line3']): ?><?php echo $store['Address']['Line3'] . "\n"; ?><?php endif; ?>
<?php echo $store['Address']['City']; ?>, <?php echo $store['Address']['StateProvinceCode']; ?> <?php echo $store['Address']['PostalCode']; ?>
</textarea>
        <br/>
        <?php echo Scrub::printEventDetails($eventTypes, $store['Address']['Id'], $store['Organization']['Id']); ?>
        <br/>
        -------------------------------------------------<br />
        <?php
    }
}

class Scrub
{
    public static function printEventDetails($eventTypes, $addressId, $organizationId)
    {
        $url = 'http://locator.wizards.com/Service/LocationService.svc/GetLocationDetails';

        $params = array(
            'language' => "en-us",
            'request' => array(
                'BusinessAddressId' => $addressId,
                'OrganizationId' => $organizationId,
                'EventTypeCodes' => $eventTypes,
                'PlayFormatCodes' => array(),
                'ProductLineCodes' => array(),
                'LocalTime' => '/Date(' . self::getDateForRequest() . ')/',
                'EarliestEventStartDate' => null,
                'LatestEventStartDate' => null
            )
        );

        $data = self::performRequest($url, $params);
        $Info = '';
        if ($data) {
            foreach ($data['d']['Result']['EventsAtVenue'] as $event) {
                if (in_array($event['EventTypeCode'], $eventTypes)) {
                    preg_match('/(\d+)/', $event['StartDate'], $matches);
                    $Info .= 'Event: ' . $event['Name'];
                    $Info .= ' Date: ' . date('Y-m-d', $matches[0] / 1000);
                    $Info .= ' Format: ' . $event['PlayFormatCode'];
                    $Info .= '<br />';
                }
            }
        }
        return $Info;
    }

    public static function getDateForRequest()
    {
        return ((int)(microtime(true) * 1000));
    }


    /**
     * @param $locationsUrl
     * @param $payload
     * @return array
     */
    public static function performRequest($locationsUrl, $payload)
    {
        $curl = curl_init($locationsUrl);
        curl_setopt_array($curl, array(
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen(json_encode($payload))
                )
            )
        );

        $result = curl_exec($curl);
        $data = json_decode($result, true);
        if (!$data) {
            echo $result;
            return null;
        }
        return $data;
    }
}