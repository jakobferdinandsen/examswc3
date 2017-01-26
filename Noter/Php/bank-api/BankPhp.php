<?php
header("access-control-allow-origin: *");
require "DatabaseConnection.php";
require_once "controller/controllerIncludes.php";
$serverMethode = $_SERVER["REQUEST_METHOD"];

processRequest($serverMethode);


function processRequest($method) {
    switch ($method) {
        case "GET":
            processGET($method);
            break;
        case "POST":
            processPOST($method);
            break;
        default:
            processBAD('400', $method, 'Bad method. Only GET');
    }
}

/*------------------------GET-----------------------*/
function processGET($method) {
    if (isset($_GET['apikey'])) {
        if ($_GET['apikey'] == DatabaseConnection::$masterKey) {
            $cmd = isset($_GET['cmd']) ? $_GET['cmd'] : 'undefined';
            switch ($cmd) {
                case 'reset':
                    $data = doReset();
                    break;
                case 'diapers_time':
                    $data = doDiapers();
                    break;
                default:
                    processBAD("400", "GET MASTER", "You can be the Master, but still an IDIOT ($cmd)");
            }
            echo '{
                "resp":{"code":"200", "status":"OK", "method":"' . $method . '"},
                "user":"Mr.Master",
                "cmd":"' . $cmd . '",
                "data":' . $data . '
                }';
        } else {
            $user = UserController::selectByKey($_GET['apikey']);
            if ($user) {
                $what = isset($_GET['what']) ? $_GET['what'] : 'undefined';
                switch ($what) {
                    case 'sell':
                        $amount = isset($_GET['amount']) ? $_GET['amount'] : 'undefined';
                        $data = sell($amount, $user);
                        break;
                    case 'offers':
                        $data = offers();
                        break;
                    case 'buy':
                        $offerId = isset($_GET['offer']) ? $_GET['offer'] : 'undefined';
                        $data = buy($offerId, $user->getId());
                        break;
                    case 'exchange_rate':
                        $fromUserName = isset($_GET['from']) ? $_GET['from'] : 'undefined';
                        $toUserName = isset($_GET['to']) ? $_GET['to'] : 'undefined';
                        $data = rate($fromUserName, $toUserName);
                        break;
                    case 'account_info':
                        $data = account_info($user->getId());
                        break;
                    default:
                        processBAD("404", $method, "No what param or unsupported, see API documentation.(what=$what)");
                }
                //TODO maybe remove user from resp msg. Lasse want user gone alex not sure
                echo '{
                "resp":{"code":"200", "status":"OK", "method":"' . $method . '"},
                "user":' . $user->toJSON() . ',
                "what":"' . $what . '",
                "data":' . $data . '
                }';
            } else { //DB returns no user with that key
                processBAD("403", $method, 'Invalid apikey provided.');
            }
        }
    } else {//no key param
        processBAD("403", $method, 'No apikey provided.');
    }
}

function sell($amount, $user) {
    if (!is_numeric($amount) || $amount < 1) {
        processBAD("403", "sell", "amount param needs to be a number >= 1. I got this ($amount)");
    }
    return TransactionsController::validateSell($user, $amount)->toJSON();
}

function buy($offerId, $userid) {
    if (($offerId == 'undefined') || (strlen(trim($offerId)) == 0)) {
        processBAD("404", 'buy', 'offer id undefined');
    } else {
        $transaction = TransactionsController::selectById($offerId);
        if ($transaction == null) {
            processBAD("404", 'buy', 'offer not found');
        } else if ($transaction->getBuytime() != null) {
            processBAD("404", 'buy', 'offer not for sell any more');
        } else {
            if ($transaction->getUserOffer()->getId() == $userid) {
                processBAD("403", 'buy', 'Not allowed to buy your own offer.');
            } else {
                return TransactionsController::validateBuy($transaction, $userid);
            }
        }
    }
}

function offers() {
    $offers = TransactionsController::selectAllOffers();
    $data = '[';
    for ($i = 0; $i < count($offers); $i++) {
        $transaction = $offers[$i];
        $data .= $transaction->toJSON();
        if (($i + 1) < count($offers)) {
            $data .= ',';
        }
    }
    return $data . ']';
}

function rate($fromUserName, $toUserName) {
    if (($fromUserName == 'undefined') || ($toUserName == 'undefined')) {
        processBAD('404', 'exchange_rate', 'from or to param undefined.');
    } else {
        $fromUser = UserController::selectByName($fromUserName);
        if ($fromUser == null) {
            processBAD('404', 'exchange_rate', "from currency($fromUserName) not found.");
        }
        $toUser = UserController::selectByName($toUserName);
        if ($toUser == null) {
            processBAD('404', 'exchange_rate', "to currency($toUserName) not found.");
        }
        if (ExtRateController::validateRate($fromUser->getId()) && ExtRateController::validateRate($toUser->getId())) {
            $fromRate = ExtRateController::selectByUserId($fromUser->getId());
            $toRate = ExtRateController::selectByUserId($toUser->getId());
            $amount = ($fromRate->getRate() / $toRate->getRate()) * 100.00;
//        var_format('in rate');
//        var_format($fromRate);
//        var_format($toRate);
//        var_format($amount);
            return '{
            "from":"' . $fromUserName . '",
            "to":"' . $toUserName . '",
            "amount":"' . $amount . '"
            }';
        }
    }
}

function account_info($userId) {
    $accounts = AccountsController::selectByUserId($userId);
    $data = '[';
    for ($i = 0; $i < count($accounts); $i++) {
        $acc = $accounts[$i];
        $data .= $acc->toJSON();
        if (($i + 1) < count($accounts)) {
            $data .= ',';
        }
    }
    return $data . ']';
}

/*------------------------POST-----------------------*/
function processPOST($method) {
    processBAD('400', $method, 'Bad method. POST not implemented yet. Only GET');
}

/*------------------------BAD-----------------------*/
function processBAD($code, $method, $msg) {
    $responseTypes = array(
        "400" => "Bad Request",
        "403" => "Forbidden",
        "404" => "Not Found",
        "405" => "Method Not Allowed",

        "500" => "Internal Server Error",
        "501" => "Not Implemented"
    );
    echo '{
    "resp":{
        "code":"' . $code . '", 
        "status":"' . $responseTypes[$code] . '", 
        "method/what":"' . $method . '",
        "msg":"' . $msg . '"
        }}';
    die;
}


/*------------------------MASTER-----------------------*/
function doDiapers() {
    $rates = ExtRateController::doChange();
    if (count($rates) == 0) {
        return "Nothing to change. ALL Babies are poop-free!";
    }
    $data = '[';
    for ($i = 0; $i < count($rates); $i++) {
        $r = $rates[$i];
        $data .= $r->toJSON();
        if (($i + 1) < count($rates)) {
            $data .= ',';
        }
    }
    return $data . ']';
}

function doReset() {
    DatabaseConnection::reset();
    if (!empty(UserController::selectAll())) {
        return "{
                'status':'done'
            }";
    } else {
        return "{
            'status:''failed'
            }";
    }
}

