<?php

header('Content-Type: application/json');

require('vendor/autoload.php');

use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Contract;
use Web3\Utils;

$web3 = new Web3(new HttpProvider(new HttpRequestManager('https://mainnet.infura.io/v3/144bc62a0f65420c9bbf7e522c621465', 10)));

$GDAOABI = '[{"inputs":[],"payable":false,"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"owner","type":"address"},{"indexed":true,"internalType":"address","name":"spender","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Approval","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"from","type":"address"},{"indexed":true,"internalType":"address","name":"to","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Transfer","type":"event"},{"constant":true,"inputs":[{"internalType":"address","name":"owner","type":"address"},{"internalType":"address","name":"spender","type":"address"}],"name":"allowance","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"approve","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"internalType":"address","name":"account","type":"address"}],"name":"balanceOf","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"burn","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"account","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"burnFrom","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"internalType":"uint8","name":"","type":"uint8"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"subtractedValue","type":"uint256"}],"name":"decreaseAllowance","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"addedValue","type":"uint256"}],"name":"increaseAllowance","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"name","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"symbol","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"totalSupply","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transfer","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"sender","type":"address"},{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transferFrom","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"}]';

$tokenAddress = "0x515d7E9D75E2b76DB60F8a051Cd890eBa23286Bc";

$airdropAddress = "0x7ea0f8bb2f01c197985c285e193dd5b8a69836c0";
$airdropBurnAddress = "0x925b1f1bd3c28ea9f03fc00b8e069ef738ff740d";
$airdropRewardAddress = "0xee6ac0ae56497c3479e858f0e9d59f5d8f8f89ea";
$mineAddress = "0x4dac3e07316d2a31baabb252d89663dee8f76f09";
$GDAOTreasury = "0xfdb3fd250698d9430949854e0fc4753c1ac42c55";
$GDAOMultisig = "0x5ab8e3a7bc8be9efdd0943ab65221bdf240518c3";


$contractHolders = [$airdropAddress, $airdropBurnAddress, $airdropRewardAddress, $mineAddress, $GDAOTreasury, $GDAOMultisig];

$circSup = 0;
global $circSup;

$GDAOContract = new Contract($web3->provider, $GDAOABI);
$GDAOContract->at($tokenAddress)->call('totalSupply', function ($err, $response) {
	global $circSup;
	$circSup = $response[0];
});

foreach ($contractHolders as $address) {
	
	$GDAOContract->at($tokenAddress)->call('balanceOf', $address, function ($err, $response) {
		global $circSup;
		$circSup = $circSup->subtract($response[0]);
	});
	
}
$result = Utils::fromWei($circSup, 'ether');

$finalCircSup = $result[0]->toString() . '.' . $result[1]->toString();

$jsonArr = array('circulatingSupply' => $finalCircSup);

echo '[' . json_encode($jsonArr) . ']';

?>
