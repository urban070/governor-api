<?php

header('Content-Type: application/json');

require('vendor/autoload.php');

use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Contract;
use Web3\Utils;

$web3 = new Web3(new HttpProvider(new HttpRequestManager('https://mainnet.infura.io/v3/144bc62a0f65420c9bbf7e522c621465', 10)));

$ERC20ABI = '[{"inputs":[],"payable":false,"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"owner","type":"address"},{"indexed":true,"internalType":"address","name":"spender","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Approval","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"from","type":"address"},{"indexed":true,"internalType":"address","name":"to","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Transfer","type":"event"},{"constant":true,"inputs":[{"internalType":"address","name":"owner","type":"address"},{"internalType":"address","name":"spender","type":"address"}],"name":"allowance","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"approve","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"internalType":"address","name":"account","type":"address"}],"name":"balanceOf","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"burn","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"account","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"burnFrom","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"internalType":"uint8","name":"","type":"uint8"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"subtractedValue","type":"uint256"}],"name":"decreaseAllowance","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"addedValue","type":"uint256"}],"name":"increaseAllowance","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"name","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"symbol","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"totalSupply","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transfer","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"sender","type":"address"},{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transferFrom","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"}]';


$WETHAddress = '0xc02aaa39b223fe8d0a0e5c4f27ead9083c756cc2';
$USDCAddress = '0xa0b86991c6218b36c1d19d4a2e9eb0ce3606eb48';
$GDAOAddress = '0x515d7E9D75E2b76DB60F8a051Cd890eBa23286Bc';

$GDAOETHLP = '0x4d184bf6f805ee839517164d301f0c4e5d25c374';
$USDCETHLP = '0xb4e16d0168e52d35cacd2c6185b44281ec28c9dc';

$tokenAddress = "0x515d7E9D75E2b76DB60F8a051Cd890eBa23286Bc";

$USDCInLP = 0;
$WETHInLP = 0;
$WETHInGDAOLP = 0;
$GDAOInLP = 0;
global $USDCInLP;
global $WETHInLP;
global $WETHInGDAOLP;
global $GDAOInLP;

$totalSup = 0;
global $totalSup;

$contract = new Contract($web3->provider, $ERC20ABI);

$contract->at($USDCAddress)->call('balanceOf', $USDCETHLP, function ($err, $response) {
	global $USDCInLP;
	$USDCInLP = $response[0];
});

$contract->at($WETHAddress)->call('balanceOf', $USDCETHLP, function ($err, $response) {
	global $WETHInLP;
	$WETHInLP = $response[0];
});

$contract->at($WETHAddress)->call('balanceOf', $GDAOETHLP, function ($err, $response) {
	global $WETHInGDAOLP;
	$WETHInGDAOLP = $response[0];
});

$contract->at($GDAOAddress)->call('balanceOf', $GDAOETHLP, function ($err, $response) {
	global $GDAOInLP;
	$GDAOInLP = $response[0];
});

$USDCInLP = Utils::fromWei($USDCInLP, 'mwei');
$WETHInLP = Utils::fromWei($WETHInLP, 'ether');
$WETHInGDAOLP = Utils::fromWei($WETHInGDAOLP, 'ether');
$GDAOInLP = Utils::fromWei($GDAOInLP, 'ether');

$USDCInLPString = $USDCInLP[0]->toString() . '.' . $USDCInLP[1]->toString();
$WETHInLPString = $WETHInLP[0]->toString() . '.' . $WETHInLP[1]->toString();
$WETHInGDAOLPString = $WETHInGDAOLP[0]->toString() . '.' . $WETHInGDAOLP[1]->toString();
$GDAOInLPString = $GDAOInLP[0]->toString() . '.' . $GDAOInLP[1]->toString();

$ETHPriceInUSDC = bcdiv($USDCInLPString, $WETHInLPString, 8);

$GDAOPriceInETH = bcdiv($WETHInGDAOLPString, $GDAOInLPString, 8);

$GDAOPriceInUSDC = bcmul($GDAOPriceInETH, $ETHPriceInUSDC, 8);

$jsonArr = array('price' => $GDAOPriceInUSDC);

echo '[' . json_encode($jsonArr) . ']';

?>
