<?php
namespace darkblog\lib;
require 'jsonRPCclient.php';
/**
 * EMERCOIN (EMC) payment gateway
 * by Neo
 */

/**
 * self payment
 *
 * @author 
 */
class emercoin {
  public static $username = '';
  public static $password = '';
  public static $address = 'localhost';
  public static $port = '8332';
  public static $account_prefix = 'emc_terminal_';
  public static $comission = 0.1;
  /**
   * The function, which returns current order ID which is usualy stored in session
   * @var integer 
   */
  public static $get_order_id;
  /**
   * The function, which returns current order ammount (EMC) which is usualy stored in session
   * @var decimal 
   */
  public static $get_order_ammount;
  public static $rpcClient;
  public static $emercoin_info;
  public static $debug = false;
  
  /**
   * Returns an object containing various state info.
   * @return array 
   */
  public static function getinfo() {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    return self::$emercoin_info = self::$rpcClient->getinfo();
  }
  
  /**
   * Create account or return an existing account address
   * @param string $account
   * @return address
   * @throws Exception
   */
  public static function getAccountAddress($account) {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    self::$emercoin_info = self::$rpcClient->getinfo();
    
    return self::$rpcClient->getaccountaddress($account);
  }
  
  public static function listaccounts() {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    self::$emercoin_info = self::$rpcClient->getinfo();
    
    return self::$rpcClient->listaccounts();
  }
  
  public static function listlabels() {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    self::$emercoin_info = self::$rpcClient->getinfo();
    
    return self::$rpcClient->listlabels();
  }
  
  public static function listAccountsAddresses() {  
    $accounts = self::listaccounts();
    $result = array();
      
    foreach ($accounts as $account => $ammount) {
        $result[$account] = self::getAddressesByAccount($account);
    }
    
    return $result;
  }
  
  public static function getFirstAddressOld() {  
    $accounts = self::listaccounts();

    foreach ($accounts as $account => $ammount) {
        $addresses = self::getAddressesByAccount($account);
        foreach ($addresses as $addr) {
            $address = $addr;
        }
    }
    return $address;
  }
  
  public static function getFirstAddress() {  
    $labels = self::listlabels();
    
    foreach ($labels as $lbl) {
      $label = $lbl;
      $addresses = self::getAddressesByLabel($label);

      foreach ($addresses as $addr => $info) {
          $info = self::getAddressInfo($addr);

          if ($info['ismine']) {
            return $addr;
          }
      }
    }

    return false;
  }

  public static function getAddressInfo($address) {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    self::$emercoin_info = self::$rpcClient->getinfo();
    
    return self::$rpcClient->getaddressinfo($address);
  }
  
  /**
   * All Addresses by account
   * @param type $account
   * @return type
   */
  public static function getAddressesByAccount($account) {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    self::$emercoin_info = self::$rpcClient->getinfo();
    
    return self::$rpcClient->getaddressesbyaccount($account);
  }
  
  /**
   * All Addresses by label
   * @param type $label
   * @return type
   */
  public static function getAddressesByLabel($label) {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    self::$emercoin_info = self::$rpcClient->getinfo();
    
    return self::$rpcClient->getaddressesbylabel($label);
  }
  
  /**
   * Function creates new account and returns it's address to recieve payments
   * The account name is saved as "self::$account_prefix+_+self::$get_order_id()"
   * @return account Payment address
   */
  public static function createPaymentAddress() {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    self::$emercoin_info = self::$rpcClient->getinfo();
    
    if(!is_callable(self::$get_order_id)) {
      throw new Exception('Property self::$get_order_id must be callable');
    }
    $account = self::$account_prefix.'_'.call_user_func(self::$get_order_id);
    return self::$rpcClient->getaccountaddress($account);
  }
  
  /**
   * The function compares the current account ammount of EMC and
   * current order ammount of EMC and returns TRUE if 
   * (account ammount) >= (order ammount)
   * The account name is "self::$account_prefix+_+self::$get_order_id()"
   * The order ammount is stored in self::$get_order_ammount()
   * @return boolean
   * @throws Exception
   */
  public static function confirmPayment() {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    self::$emercoin_info = self::$rpcClient->getinfo();
    
    if(!is_callable(self::$get_order_id)) {
      throw new Exception('Property self::$get_order_id must be callable');
    }
    $account = self::$account_prefix.'_'.call_user_func(self::$get_order_id);
    
    if(!is_callable(self::$get_order_ammount)) {
      throw new Exception('Property self::$get_order_ammount must be callable');
    }
    $order_ammount = call_user_func(self::$get_order_ammount);
    
    
    try {
      $received_amount = self::$rpcClient->getreceivedbyaccount($account, 0);
    } catch (Exception $e) {
            echo false;
    }
    if((float)$received_amount >= (float)$order_ammount) {
            return true;
    }
    else {
            return false;
    }
  }
  
  /**
   * Return the balance from account
   * @param type $account
   * @return float
   */
  public static function getAccauntBalance($account) {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    self::$emercoin_info = self::$rpcClient->getinfo();
    
    return self::$rpcClient->getbalance($account);
  }
  
  /**
   * Return total from the wallet
   * @return float
   */
  public static function getAllBalance() {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    self::$emercoin_info = self::$rpcClient->getinfo();
    
    return self::$rpcClient->getbalance();
  }
  
  /**
   * Send some EMC to address
   * 
   * @param type $emercoinaddress
   * @param type $amount
   * @param type $account - send account from
   * @return type 
   */
  public static function sendToAddress($emercoinaddress, $amount, $account = '') {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    self::$emercoin_info = self::$rpcClient->getinfo();
    
    if($account == '') {
      return self::$rpcClient->sendtoaddress($emercoinaddress, (double)$amount);
    }
    else {
      return self::$rpcClient->sendfrom($account, $emercoinaddress, (double)$amount);
    }
  }
  
  /**
   * Send ALL your EMC to address
   * @param type $emercoinaddress
   * @return type
   */
  public static function sendAllToAddress($emercoinaddress) {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    self::$emercoin_info = self::$rpcClient->getinfo();
    
    $amount = self::getAllBalance();
    
    return self::$rpcClient->sendtoaddress($emercoinaddress, (double)$amount);
  }
  
  /**
   * 
   * @param type $account
   * @return type 
   */
  public static function createNewAddress($account) {
      $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
      self::$rpcClient = new jsonRPCClient($url, self::$debug);

      self::$emercoin_info = self::$rpcClient->getinfo();

      return self::$rpcClient->getnewaddress($account);
  }
  
  /**
   * Total recieved by address
   * @param type $emercoinaddress
   * @return type
   */
  public static function getRecievedByAddress($emercoinaddress) {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    return self::$rpcClient->getreceivedbyaddress($emercoinaddress);
  }
  
  /**
   * Trasaction list
   * @param type $account
   * @param type $count
   * @return type
   */
  public static function listtransactions($account, $count = 1000) {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    return self::$rpcClient->listtransactions($account, $count);
  }  
  
  /**
   * Last transaction
   * @param type $account
   * @param type $address
   * @param type $count
   * @return type
   */
  public static function getLastReceivedTransaction($account, $address, $count = 1000) {
    //echo " getLastReceivedTransaction($account, $address, $count = 1000) ";
    $transactions = self::listtransactions($account, $count);
    //var_dump($transactions);
    $tx_hight = count($transactions) - 1;
    for($i = $tx_hight; $i >= 0; $i--) {
      if(($transactions[$i]['category'] == 'receive') && ($transactions[$i]['address'] == $address)) {
        return $transactions[$i];
      }
    }
  }  

  /**
   * Sign a message with the private key of an address
        Requires wallet passphrase to be set with walletpassphrase call.
   * @param type $emercoinaddress The emercoin address to use for the private key.
   * @param type $message The message to create a signature of.
   * @return type
   */
  public static function signmessage($emercoinaddress, $message) {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    return self::$rpcClient->signmessage($emercoinaddress, $message);
  }


  /**
   * Verify a signed message
   * @param type $emercoinaddress The emercoin address to use for the signature.
   * @param type $signature The signature provided by the signer in base 64 encoding (see signmessage).
   * @param type $message The message that was signed.
   * @return type
   */
  public static function verifymessage($emercoinaddress, $signature, $message) {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    return self::$rpcClient->verifymessage($emercoinaddress, $signature, (string)$message);
  }

  /**
   * scan and filter names
   * name_filter "" 5 # list names updated in last 5 blocks
   * name_filter "^id/" # list all names from the "id" namespace
   * name_filter "^id/" 0 0 0 stat # display stats (number of names) on active names from the "id" namespace
   * @param type $regexp apply [regexp] on names, empty means all names
   * @param int $maxage look in last [maxage] blocks
   * @param int $from show results from number [from]
   * @param int $nb show [nb] results, 0 means all
   * @param type $stat show some stats instead of results
   * @param type $valuetype if "hex" or "base64" is specified then it will print value in corresponding format instead of string.

   * @return type
   */
  public static function name_filter( $regexp, $maxage=0, $from=0, $nb=0, $stat='', $valuetype='') {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    self::$emercoin_info = self::$rpcClient->getinfo();

    if(!empty($valuetype)) {
        return self::$rpcClient->name_filter( $regexp, $maxage, $from, $nb, $stat, $valuetype);
    }
    elseif(!empty($stat)) {
        return self::$rpcClient->name_filter( $regexp, $maxage, $from, $nb, $stat);
    }
    else {
        return self::$rpcClient->name_filter( $regexp, $maxage, $from, $nb);
    }
  }
  
  /**
   * Look up the current and all past data for the given name.
   * @param type $name
   * @param type $fullhistory
   * @param type $valuetype
   * @return type
   */
  public static function name_history( $name , $fullhistory, $valuetype) {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    return self::$rpcClient->name_history( $name , $fullhistory, $valuetype);
  }
  
  /**
   * list pending name transactions in mempool.
   * @param type $valuetype
   * @return type
   */
  public static function name_mempool( $valuetype ) {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    return self::$rpcClient->name_mempool($valuetype);
  }
  
  /**
   * Scan all names, starting at start-name and returning a maximum number of entries (default 500)
   * @param type $start_name
   * @param type $max_returned
   * @param type $max_value_length
   * @param type $valuetype
   * @return type
   */
  public static function name_scan( $start_name, $max_returned, $max_value_length=-1, $valuetype='') {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    return self::$rpcClient->name_scan($start_name, $max_returned, $max_value_length, $valuetype);
  }
  
    /**
   * List my own names.
   * @param type $name (string, required) Restrict output to specific name
   * @param type $valuetype (string, optional) If "hex" or "base64" is specified then it will print value in corresponding format instead of string.
   * @return type
   */
  public static function name_list( $name, $valuetype='') {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    return self::$rpcClient->name_list($name, $valuetype);
  }
  
  /**
   * List my own names (filtered).
   * @param type $name_start first part of a name
   * @param type $valuetype
   */
  public static function name_list_filtered( $name_start, $valuetype='') {
      $records = self::name_list("", $valuetype);
      $result = array();
      
      foreach ($records as $record) {
          if(strpos($record['name'], $name_start) === 0) {
              $result[] = $record;
          }
      }
      
      return $result;
  }
  
  /**
   * Show values of a name.
   * @param type $name
   * @param type $valuetype If "hex" or "base64" is specified then it will print value in corresponding format instead of string.
   * @param type $filepath save name value in binary format in specified file (file will be overwritten!).
   * @return type
   */
  public static function name_show( $name, $valuetype='', $filepath='') {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    if(!empty($filepath)) {
        return self::$rpcClient->name_show($name, $valuetype, $filepath);
    }
    elseif(!empty($valuetype)) {
        return self::$rpcClient->name_show($name, $valuetype);
    }
    else {
        return self::$rpcClient->name_show($name);
    }
    
    
  }
  
  public static function name_new($name, $value, $days, $toaddress = '', $valuetype = "") {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    //return self::$rpcClient->name_new($name, $value, $days);
    
    if(!empty($toaddress)) {
        return self::$rpcClient->name_new($name, $value, $days, $toaddress);
    }
    elseif(!empty($valuetype)) {
        return self::$rpcClient->name_new($name, $value, $days, $toaddress, $valuetype);
    }
    else {
        return self::$rpcClient->name_new($name, $value, $days);
    }
  }
  
  public static function names_new($values, $days, $toaddress = '', $valuetype = "") {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    //return self::$rpcClient->name_new($name, $value, $days);
    
    if(!empty($toaddress)) {
        foreach ($values as $name => $value)
            self::$rpcClient->name_new($name, $value, $days, $toaddress);
    }
    elseif(!empty($valuetype)) {
        foreach ($values as $name => $value)
            self::$rpcClient->name_new($name, $value, $days, $toaddress, $valuetype);
    }
    else {
        foreach ($values as $name => $value)
            self::$rpcClient->name_new($name, $value, $days);
    }
  }
  
  public static function name_update( $name, $value, $days, $toaddress='', $valuetype='') {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    //return self::$rpcClient->name_update($name, $value, $days);
    
    if(!empty($toaddress)) {
        return self::$rpcClient->name_update($name, $value, $days, $toaddress);
    }
    elseif(!empty($valuetype)) {
        return self::$rpcClient->name_update($name, $value, $days, $toaddress, $valuetype);
    }
    else {
        return self::$rpcClient->name_update($name, $value, $days);
    }
  }
  
  public static function name_delete( $name ) {
    $url = self::$username.':'.self::$password.'@'.self::$address.':'.self::$port.'/';
    self::$rpcClient = new jsonRPCClient($url, self::$debug);
    
    return self::$rpcClient->name_delete( $name );
  }
}

/**

== Blockchain ==
getbestblockhash
getblock "blockhash" ( verbosity )
getblockchaininfo
getblockcount
getblockfilter "blockhash" ( "filtertype" )
getblockhash height
getblockheader "blockhash" ( verbose )
getblockstats hash_or_height ( stats )
getchaintips
getchaintxstats ( nblocks "blockhash" )
getdifficulty
getmempoolancestors "txid" ( verbose )
getmempooldescendants "txid" ( verbose )
getmempoolentry "txid"
getmempoolinfo
getrawmempool ( verbose )
gettxout "txid" n ( include_mempool )
gettxoutproof ["txid",...] ( "blockhash" )
gettxoutsetinfo
name_delete "name"
name_filter [regexp] [maxage=0] [from=0] [nb=0] [stat] [valuetype]
name_history <name> [fullhistory] [valuetype]
name_indexinfo
name_list [name] [valuetype]
name_mempool [valuetype]
name_new "name" "value" days ( "toaddress" "valuetype" )
name_scan [start-name] [max-returned] [max-value-length=0] [valuetype]
name_scan_address <address> [max-value-length=0] [valuetype]
name_show <name> [valuetype] [filepath]
name_update "name" "value" days ( "toaddress" "valuetype" )
name_updatemany [{"NEW/UPDATE/DELETE":"str","value":"str","days":n,"toaddress":"str","valuetype":"str"},...]
preciousblock "blockhash"
pruneblockchain height
savemempool
scantxoutset "action" ( [scanobjects,...] )
sendtoname "name" amount ( "comment" "comment_to" )
verifychain ( checklevel nblocks )
verifytxoutproof "proof"

== Control ==
getinfo
getmemoryinfo ( "mode" )
getrpcinfo
help ( "command" )
logging ( ["include_category",...] ["exclude_category",...] )
stop
uptime

== Generating ==
generate nblocks ( maxtries )
generatetoaddress nblocks "address" ( maxtries )

== Mining ==
getauxblock ( "hash" "auxpow" )
getblocktemplate ( "template_request" )
getmininginfo
getnetworkhashps ( nblocks height )
prioritisetransaction "txid" ( dummy ) fee_delta
submitblock "hexdata" ( "dummy" )
submitheader "hexdata"

== Network ==
addnode "node" "command"
clearbanned
disconnectnode ( "address" nodeid )
getaddednodeinfo ( "node" )
getcheckpoint
getconnectioncount
getnettotals
getnetworkinfo
getnodeaddresses ( count )
getpeerinfo
listbanned
ping
setban "subnet" "command" ( bantime absolute )
setnetworkactive state

== Randpay ==
randpay_accept hexstring ( flags )
randpay_mkchap amount risk timeout
randpay_mktx "chap" timeout ( flags )

== Rawtransactions ==
analyzepsbt "psbt"
combinepsbt ["psbt",...]
combinerawtransaction ["hexstring",...]
converttopsbt "hexstring" ( permitsigdata iswitness )
createpsbt [{"txid":"hex","vout":n,"sequence":n},...] [{"address":amount},{"data":"hex"},...] ( locktime replaceable )
createrawtransaction [{"txid":"hex","vout":n,"sequence":n},...] [{"address":amount},{"data":"hex"},...] ( locktime replaceable )
decodepsbt "psbt"
decoderawtransaction "hexstring" ( iswitness )
decodescript "hexstring"
finalizepsbt "psbt" ( extract )
fundrawtransaction "hexstring" ( options iswitness )
getrawtransaction "txid" ( verbose "blockhash" )
joinpsbts ["psbt",...]
sendrawtransaction "hexstring" ( maxfeerate )
signrawtransactionwithkey "hexstring" ["privatekey",...] ( [{"txid":"hex","vout":n,"scriptPubKey":"hex","redeemScript":"hex","witnessScript":"hex","amount":amount},...] "sighashtype" )
testmempoolaccept ["rawtx",...] ( maxfeerate )
utxoupdatepsbt "psbt" ( ["",{"desc":"str","range":n or [n,n]},...] )

== Util ==
createmultisig nrequired ["key",...] ( "address_type" )
deriveaddresses "descriptor" ( range )
estimatesmartfee conf_target ( "estimate_mode" )
getdescriptorinfo "descriptor"
signmessagewithprivkey "privkey" "message"
validateaddress "address"
verifymessage "address" "signature" "message"

== Wallet ==
abandontransaction "txid"
abortrescan
addmultisigaddress nrequired ["key",...] ( "label" "address_type" )
backupwallet "destination"
createwallet "wallet_name" ( disable_private_keys blank "passphrase" avoid_reuse )
dumpprivkey "address"
dumpwallet "filename"
encryptwallet "passphrase"
getaddressesbylabel "label"
getaddressinfo "address"
getbalance ( "dummy" minconf include_watchonly avoid_reuse )
getbalances
getnewaddress ( "label" "address_type" )
getrawchangeaddress ( "address_type" )
getreceivedbyaddress "address" ( minconf )
getreceivedbylabel "label" ( minconf )
gettransaction "txid" ( include_watchonly verbose )
getunconfirmedbalance
getwalletinfo
importaddress "address" ( "label" rescan p2sh )
importmulti "requests" ( "options" )
importprivkey "privkey" ( "label" rescan )
importprunedfunds "rawtransaction" "txoutproof"
importpubkey "pubkey" ( "label" rescan )
importwallet "filename"
keypoolrefill ( newsize )
listaddressgroupings
listlabels ( "purpose" )
listlockunspent
listreceivedbyaddress ( minconf include_empty include_watchonly "address_filter" )
listreceivedbylabel ( minconf include_empty include_watchonly )
listsinceblock ( "blockhash" target_confirmations include_watchonly include_removed )
listtransactions ( "label" count skip include_watchonly )
listunspent ( minconf maxconf ["address",...] include_unsafe query_options )
listwalletdir
listwallets
loadwallet "filename"
lockunspent unlock ( [{"txid":"hex","vout":n},...] )
makekeypair ( prefix )
removeprunedfunds "txid"
rescanblockchain ( start_height stop_height )
reservebalance ( reserve amount )
sendmany "" {"address":amount} ( minconf "comment" ["address",...] replaceable conf_target "estimate_mode" )
sendtoaddress "address" amount ( "comment" "comment_to" subtractfeefromamount replaceable conf_target "estimate_mode" avoid_reuse )
sethdseed ( newkeypool "seed" )
setlabel "address" "label"
settxfee amount
setwalletflag "flag" ( value )
signmessage "address" "message"
signrawtransactionwithwallet "hexstring" ( [{"txid":"hex","vout":n,"scriptPubKey":"hex","redeemScript":"hex","witnessScript":"hex","amount":amount},...] "sighashtype" )
unloadwallet ( "wallet_name" )
walletcreatefundedpsbt [{"txid":"hex","vout":n,"sequence":n},...] [{"address":amount},{"data":"hex"},...] ( locktime options bip32derivs )
walletlock
walletpassphrase "passphrase" timeout ( mintonly )
walletpassphrasechange "oldpassphrase" "newpassphrase"
walletprocesspsbt "psbt" ( sign "sighashtype" bip32derivs )

== Zmq ==
getzmqnotifications


 */