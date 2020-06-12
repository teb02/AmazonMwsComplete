<?php

namespace CaponicaAmazonMwsComplete\ClientPack;

use CaponicaAmazonMwsComplete\AmazonClient\MwsSellerClient;
use CaponicaAmazonMwsComplete\ClientPool\MwsClientPoolConfig;
use CaponicaAmazonMwsComplete\Concerns\ProvidesServiceUrlSuffix;
use CaponicaAmazonMwsComplete\Concerns\SignsRequestArray;

class MwsSellersClientPack extends MwsSellerClient {
	use SignsRequestArray, ProvidesServiceUrlSuffix;

	const SERVICE_NAME = 'Sellers';

	const PARAM_NEXT_TOKEN = 'NextToken';

	/** @var string $marketplaceId      The MWS MarketplaceID string used in API connections */
	protected $marketplaceId;
	/** @var string $sellerId           The MWS SellerID string used in API connections */
	protected $sellerId;
	/** @var string $authToken          MWSAuthToken, only needed when working with (3rd party) client accounts which provide an Auth Token */
	protected $authToken = null;

	public function __construct(MwsClientPoolConfig $poolConfig) {
		$this->marketplaceId    = $poolConfig->getMarketplaceId();
		$this->sellerId         = $poolConfig->getSellerId();
		$this->authToken        = $poolConfig->getAuthToken();

		parent::__construct(
			$poolConfig->getAccessKey(),
			$poolConfig->getSecretKey(),
			$poolConfig->getApplicationName(),
			$poolConfig->getApplicationVersion(),
			$poolConfig->getConfigForOrder($this->getServiceUrlSuffix())
		);
	}

	// ##################################################
	// #      basic wrappers for API calls go here      #
	// ##################################################
	public function callGetServiceStatus() {
		$requestArray = $this->signArray();
		return $this->getServiceStatus($requestArray);
	}

	/**
	 * @return \MarketplaceWebServiceSellers_Model_ListMarketplaceParticipationsResponse
	 */
	public function callListMarketplaceParticipations() {
		$requestArray = $this->signArray([]);
		return $this->listMarketplaceParticipations($requestArray);
	}

	/**
	 * @param string $nextToken
	 * @return \MarketplaceWebServiceSellers_Model_ListMarketplaceParticipationsByNextTokenResponse
	 */
	public function callListMarketplaceParticipationsByNextToken($nextToken) {
		$requestArray = [
			self::PARAM_NEXT_TOKEN => $nextToken,
		];
		$requestArray = $this->signArray($requestArray);
		return $this->listMarketplaceParticipationsByNextToken($requestArray);
	}
}
