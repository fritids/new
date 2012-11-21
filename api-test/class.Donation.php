<?php

require_once dirname(__FILE__).'/class.ConnectionHandler.php';

class Donation
{
	const API_KEY = 'DONORSCHOOSE'; //q23343aikzh5
	const API_PASS = 'helpClassrooms!';
	const ACTION = 'donate';

	private $_search_url = 'http://api.donorschoose.org/common/json_feed.html';
	private $_search_test_url = 'http://apiqa.donorschoose.org/common/json_feed.html';
	private $_donation_url = 'https://apisecure.donorschoose.org/common/json_api.html';
	private $_donation_test_url = 'https://apisecureqa.donorschoose.org/common/json_api.html';
	private $_parameters;
	private $_connection;
	private $_project_data;
	private $_paid_projects;
	private $_donation_amount;

	function __construct($options)
	{
		$options['APIKey'] = self::API_KEY;
		$options['apipassword'] = self::API_PASS;
		$options['action'] = self::ACTION;
		$this -> _parameters = $options;
		$this -> _paid_projects = array();
		$this -> _connection = new ConnectionHandler();
	}

	public function donate($amount)
	{
		$this -> _donation_amount = $amount;
		$project_data = $this -> _get_donation_project();
		return $this -> _check_ammount_to_donate($amount, $project_data);
	}

	/**
	 *
	 * Get data for the project for which we send money to
	 */
	private function _get_donation_project()
	{
		$this -> _connection -> set_url($this -> _search_test_url);

		// should find donation project on random in any category
		$this -> _connection -> set_query(http_build_query(array(
														'highLevelPoverty' => true,
														'gradeType'=>4,
														'sortBy'=>0,
														'costToComplete'=>4,
														'APIKey' => self::API_KEY)
												));
		$status = $this -> _connection -> post_data();
		//print_r($status);
		//return;
		// get random project for donation
		$proposals = json_decode($status);
		$proposals = $proposals -> proposals;
		$length = sizeof($proposals);
		$rand = rand(0, $length - 1);
		$project = $proposals[$rand];
		print_r($project);
		if($project -> costToComplete == 0) $this -> _get_donation_project();
		//if(in_array($project -> id, $this -> _paid_projects)) $this -> _get_donation_project();

		$project_data = array(
			'project_id' => intval($project -> id),
			'cost_to_complete' => floatval($project -> costToComplete)
		);
		return $project_data;
	}

	/**
	 *
	 * Enter description here ...
	 * @param float $amount
	 */
	public function _check_ammount_to_donate($amount, $project_data)
	{
		print_r($project_data);
		print_r($amount . ' | ' . $project_data['cost_to_complete']);

		if($amount > $project_data['cost_to_complete'])
		{
			//$t = intval($project_data['cost_to_complete'] - ($project_data['cost_to_complete'] * 0.2));
			$status = $this -> _send_donation($project_data['cost_to_complete'], $project_data['project_id']);

			//print_r($status);
			/*if($status -> statusCode == 0)
			{
				//$this -> _donation_amount = floatval($amount) - $project_data['cost_to_complete'];
				//$this -> _donation_amount = intval($amount - $t);
				//$this -> _paid_projects[] = $id;//$project_data['project_id'];

				$this -> _donation_amount -= $amount;
				$p_data = $this -> _get_donation_project();
				$this -> _check_ammount_to_donate($this -> _donation_amount, $p_data);
			}
			elseif( $status -> statusCode == 2 || $status -> statusCode == 3)
			{
				$remaining_amount = $status -> remainingProposalAmount;
				//$p_data = $this -> _get_donation_project();
				$project_data['cost_to_complete'] = $remaining_amount;
				$this -> _check_ammount_to_donate($remaining_amount, $project_data);
			}
			else
			{
				return $status -> statusDescritpion;
			}*/
		}
		else
		{
			return $this -> _send_donation($amount, $project_data['project_id']);
		}
	}

	/**
	 *
	 * Send donation
	 * @param float $amount
	 */
	private function _send_donation($amount, $project_id)
	{
		// donate
		$this -> _parameters['proposalId'] = $project_id;
		$this -> _parameters['amount'] = $amount;
		$this -> _connection -> set_url($this -> _donation_test_url);
		$this -> _connection -> set_query(http_build_query($this -> _parameters));
		//print_r(http_build_query($this -> _parameters));
		return json_decode($this -> _connection -> post_data());
	}

	/**
	 *
	 * Set internal properties to mirror the options on init
	 * @param array $options
	 */
	private function _set_properties($options)
	{
		if(is_array($options))
		{
			foreach($options as $k => $option)
			{
				$this -> {'$_'.$k} = $option;
			}
		}
	}

	/**
	 *
	 * If there are errors using http_build_query, create url friendly parameters
	 */
	private function _set_url_query()
	{
		$paramsJoined = array();

		foreach($this -> _parameters as $param => $value) {
		   $paramsJoined[] = "$param=$value";
		}

		$query = implode('&', $paramsJoined);

		return $query;
	}

	/**
	 *
	 * Enter description here ...
	 */
	private function _donation_callback()
	{

	}
}

?>