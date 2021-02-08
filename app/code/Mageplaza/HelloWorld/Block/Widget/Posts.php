<?php 
namespace Mageplaza\HelloWorld\Block\Widget;

use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface; 
use Mageplaza\HelloWorld\Model\PostcodeFactory;

use GuzzleHttp\Client;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ResponseFactory;
use Magento\Framework\Webapi\Rest\Request;

class Posts extends Template implements BlockInterface {

	protected $_template = "widget/posts.phtml";

	/**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * API request URL
     */
    const API_REQUEST_URI = 'https://api.github.com/';

    /**
     * API request endpoint
     */
    const API_REQUEST_ENDPOINT = 'repos/';

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var ClientFactory
     */
    private $clientFactory;

    private $postcodeFactory;

    /**
     * Page constructor.
     *
     * @param Template\Context $context
     * @param SerializerInterface $serializer
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ClientFactory $clientFactory,
        ResponseFactory $responseFactory,
        SerializerInterface $serializer,
        PostcodeFactory $postcodeFactory,
        array $data = []
    ) {
        $this->serializer = $serializer;
        $this->clientFactory = $clientFactory;
        $this->postcodeFactory = $postcodeFactory;
        $this->responseFactory = $responseFactory;
        parent::__construct($context, $data);
    }
    public function getConfigData()
    {
        $dropdown = [
            [
                'label' => 'Option 1',
                'value' => 'option1',
                'dropdown' => [
                    [
                        'label' => 'Option 11',
                        'value' => 'option11'
                    ],
                    [
                        'label' => 'Option 12',
                        'value' => 'option12'
                    ]
                ]
            ],
            [
                'label' => 'Option 2',
                'value' => 'option2',
                'dropdown' => [
                    [
                        'label' => 'Option 21',
                        'value' => 'option21'
                    ]
                ]
            ],
            [
                'label' => 'Option 3',
                'value' => 'option3',
                'dropdown' => [
                    [
                        'label' => 'Option 31',
                        'value' => 'option31'
                    ],
                    [
                        'label' => 'Option 32',
                        'value' => 'option32'
                    ]
                ]
            ]
        ];
        return $this->serializer->serialize(['dropdown' => $dropdown]);
    }

    public function getCollection(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$customerFactory = $objectManager->get('Mageplaza\HelloWorld\Model\PostcodeFactory')->create();

$customerId = 1;

$customer = $customerFactory->load(1);
echo "<pre>";
$data1 = $customer->getData();
print_r($customer->getData());
$customer = $customerFactory->load(6);
$data2 = $customer->getData();
print_r($customer->getData());

$lat1 = 23.038547; // $data1['latitude'];
$lon1 = 72.512788; // $data1['longitude'];
$lat2 = 22.990960; // $data2['latitude'];
$lon2 = 72.610558; // $data2['longitude'];
$unit = 'K';

$theta = $lon1 - $lon2;
$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
$dist = acos($dist);
$dist = rad2deg($dist);
$miles = $dist * 60 * 1.1515;
$unit = strtoupper($unit);

if ($unit == "K") {
    echo ($miles * 1.609344);
} else if ($unit == "N") {
    echo ($miles * 0.8684);
} else {
    echo $miles;
}
exit();
$dist = sin(deg2rad((double)$lat1)) * sin(deg2rad((double)$lat2)) + cos(deg2rad((double)$lat1)) * cos(deg2rad((double)$lat2)) * cos(deg2rad((double)$theta));
$dist = acos($dist);
$dist = rad2deg($dist);
$distance = round($dist * 60 * 1.1515 * 1.609344);
echo $distance;
print_r($distance);
        // var_dump($this->postcodeFactory->create()->getCollection()->addFieldToFilter('id', 1));
    }

    /**
     * Fetch some data from API
     */
    public function fetchGitData(): string
    {
        $postcode1 = 'BH151DA';
        $postcode2 = 'BH213AP';

        // Set and retrieve the query URL
        $request = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $postcode1 . "&destinations=" . $postcode2 . "&mode=driving&language=en-GB&sensor=false&units=imperial";
        $distdata = file_get_contents($request);
// var_dump($distdata);

        $repositoryName = 'magento/magento2';
        $response = $this->doRequest(static::API_REQUEST_ENDPOINT . $repositoryName);
        $status = $response->getStatusCode(); // 200 status code
        $responseBody = $response->getBody();
        $responseContent = $responseBody->getContents(); // here you will have the API response in JSON format
        // var_dump($status);
        return $responseContent;
        // Add your logic using $responseContent
    }

    /**
     * Do API request with provided params
     *
     * @param string $uriEndpoint
     * @param array $params
     * @param string $requestMethod
     *
     * @return Response
     */
    private function doRequest(
        string $uriEndpoint,
        array $params = [],
        string $requestMethod = Request::HTTP_METHOD_GET
    ): Response {
        /** @var Client $client */
        $client = $this->clientFactory->create(['config' => [
            'base_uri' => self::API_REQUEST_URI
        ]]);

        try {
            $response = $client->request(
                $requestMethod,
                $uriEndpoint,
                $params
            );
        } catch (GuzzleException $exception) {
            /** @var Response $response */
            $response = $this->responseFactory->create([
                'status' => $exception->getCode(),
                'reason' => $exception->getMessage()
            ]);
        }

        return $response;
    }
}