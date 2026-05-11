<?php
include "config/config_session.php";
include "config/config.php";
include "model/ipcounselModel.php";
include "control/ipcounselControl.php";
include "view/ipcounselView.php";

if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $action = $_POST["ipc_action"];
        $argument_disc_id = $_POST["argument_disc_id"];
        $argument_text = $_POST["argument_text"];

<<<<<<< HEAD
        $control = new IpcounselControl($_SESSION["user_id"],$action,$argument_disc_id, $argument_text);
=======
        $control = new Ipcounsel($_SESSION["user_id"],$action,$argument_disc_id, $argument_text);
>>>>>>> 464c9faf0a2c3baf7a27a165fdfb66d77c292f3e
        if($control->submitIpCounselAction())
            {
                header("Location: ../public/ip_counsel/ipcounsel.php?action=success");
                exit();
            }
        else
            {
                $_SESSION['errorIpcounsel'] = $control->errors;
                header("Location: ../public/ip_counsel/ipcounsel.php?action=error");
                exit();
            }

        exit();
    }
    else
    {
        header("Location: ../public/index.php?login=expired_session");
        exit();
    }