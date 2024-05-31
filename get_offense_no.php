<?php

require_once('./config.php');

// $qry = $conn->query("SELECT DATE_FORMAT(date_commited, '%Y-%m') AS month_year, COUNT(*) AS commit_count FROM ir_list  WHERE emp_no = '{$_GET['i']}' and valid = 1 and code_no = '{$decodedBadge}' GROUP BY month_year")->num_rows;
// $qry = $conn->query("SELECT id FROM ir_list WHERE emp_no = '{$_GET['i']}' AND valid = 1 AND code_no = '{$decodedBadge}' AND DATE_FORMAT(date_commited, '%m/%Y') = DATE_FORMAT(NOW(), '%m/%Y')")->num_rows;
$badge = $_GET['q'];
$decodedBadge = urldecode($badge);

if ($decodedBadge == 'A-#12' && !empty($_GET['date_commited'])) {
    $qry = $conn->query("SELECT id FROM ir_list WHERE emp_no = '{$_GET['i']}' AND valid = 1 AND cleansed = 0 AND code_no = '{$decodedBadge}' AND DATE_FORMAT(date_commited, '%Y-%m') = DATE_FORMAT('{$_GET['date_commited']}', '%Y-%m')")->num_rows;

    $_SESSION['date_commited_array'] = $_SESSION['date_commited_array'] ?? [];


    // Push the new date into the array

    $indexToRemove = $_GET['arr_index'] - 1;

    if (isset($_SESSION['date_commited_array'][$indexToRemove])) {
        unset($_SESSION['date_commited_array'][$indexToRemove]);
    } else {
        array_splice($_SESSION['date_commited_array'], $indexToRemove, 0, $_GET['date_commited']);
    }
    // Assuming $_SESSION['date_commited_array'] is an array of date strings
    $dateCommitedArray = $_SESSION['date_commited_array'];

    // Step 1: Explode the string into an array
    // (This step might not be necessary if $_SESSION['date_commited_array'] is already an array)
    //$dateCommitedArray = explode(', ', $dateCommitedString);

    // Step 2: Convert the array of date strings into an array of DateTime objects
    $dateTimeArray = [];
    foreach ($dateCommitedArray as $dateString) {
        $dateTimeArray[] = new DateTime($dateString);
    }

    // Step 3: Sort the array of DateTime objects
    usort($dateTimeArray, function ($a, $b) {
        return $a <=> $b; // This comparison sorts from oldest to latest
    });

    // Step 4: Format the sorted DateTime objects back into date strings
    $sortedDateCommitedArray = [];
    foreach ($dateTimeArray as $dateTime) {
        $sortedDateCommitedArray[] = $dateTime->format('Y-m-d'); // Adjust the format as needed
    }

    // Finally, you can implode the sorted array back into a string
    $sortedDateCommitedString = implode(', ', $sortedDateCommitedArray);


    // Split the input string into an array of date strings
    $dateArray = explode(', ', $sortedDateCommitedString);

    // Create an associative array to store counts for each month
    $monthCounts = array();

    // Loop through each date
    foreach ($dateArray as $dateString) {
        // Parse the date string
        $date = new DateTime($dateString);

        // Extract the month and year
        $monthYear = $date->format('n/Y');

        // Increment the count for the month
        $monthCounts[$monthYear] = isset($monthCounts[$monthYear]) ? $monthCounts[$monthYear] + 1 : 1;
    }

    // Convert the monthCounts array values to an array
    $count = array_values($monthCounts);

    // $array_count =  implode(', ', $count);

    $highestIndex = end($count);
    // $hint = $highestIndex;

    // $_SESSION['sub_count'] = isset($_SESSION['sub_count']) ? $_SESSION['sub_count'] : 0;

    $modifiedQry = $qry + 1;
    $hint = '';


    // Note: Adjust the logic inside the switch-case based on your specific requirements.

    if ($qry == 0) {
        if ($modifiedQry % 3 == 1 && $highestIndex % 3 == 1) {
            $hint = '1 out of 3';
        } elseif ($modifiedQry % 3 == 1 && $highestIndex % 3 == 2) {
            $hint = '2 out of 3';
        } elseif ($modifiedQry % 3 == 2 && $highestIndex % 3 == 1) {
            $hint = '1 out of 3';
        } elseif ($modifiedQry % 3 == 2 && $highestIndex % 3 == 2) {
            $hint = '2 out of 3';
        } else {
            $hint = $highestIndex / 3;
        }
    } elseif ($qry % 3 == 1) {
        if ($_GET['count'] % 3 == 1) {
            if ($modifiedQry % 3 == 1) {
                $hint = '1 out of 3';
            } elseif ($modifiedQry % 3 == 2) {
                $hint = '2 out of 3';
            } else {
                $hint = $modifiedQry  / 3;
            }
        } elseif ($_GET['count'] % 3 == 2) {
            if ($modifiedQry == 2) {
                $hint = ($_GET['count'] + $qry)  / 3;
            } else {
                $hint = '2 out of 3';
            }
        } else {
            if ($modifiedQry == 2) {
                $hint = '1 out of 3';
            }
        }
    } elseif ($qry % 3 == 2) {
        if ($_GET['count'] % 3 == 1) {
            $hint = ($qry + $_GET['count'])  / 3;
        } elseif ($_GET['count'] % 3 == 2) {
            $hint = '1 out of 3';
        } else {
            if ($modifiedQry % 3 == 0 && $_GET['count'] % 3 == 0) {
                $hint = '2 out of 3';
            } else {
                $hint = $_GET['count']  / 3;
            }
        }
    }


    // $qry = $conn->query("SELECT * FROM `ir_list` WHERE emp_no = '{$_GET['i']}' and valid = 1 and code_no = '" . $decodedBadge . "'")->num_rows;
} elseif ($decodedBadge == 'AQ-#2' && !empty($_GET['aq_date_commited'])) {
    $aq_qry = $conn->query("SELECT id FROM ir_list  WHERE emp_no = '{$_GET['i']}' AND valid = 1 AND cleansed = 0 AND code_no = '{$decodedBadge}' AND WEEK(date_commited) = WEEK('{$_GET['aq_date_commited']}')")->num_rows;
    $aq_modifiedQry = $aq_qry + 1;

    $_SESSION['date_commited_array_aq'] = $_SESSION['date_commited_array_aq'] ?? [];


    // Push the new date into the array

    $aq_indexToRemove = $_GET['aq_arr_index'] - 1;

    if (isset($_SESSION['date_commited_array_aq'][$aq_indexToRemove])) {
        unset($_SESSION['date_commited_array_aq'][$aq_indexToRemove]);
    } else {
        array_splice($_SESSION['date_commited_array_aq'], $aq_indexToRemove, 0, $_GET['aq_date_commited']);
    }
    // Assuming $_SESSION['date_commited_array_aq'] is an array of date strings
    $aq_dateCommitedArray = $_SESSION['date_commited_array_aq'];

    // Convert the array of date strings into an array of DateTime objects
    $aq_dateTimeArray = [];
    foreach ($aq_dateCommitedArray as $aq_dateString) {
        $aq_dateTimeArray[] = new DateTime($aq_dateString);
    }

    // Sort the array of DateTime objects
    usort($aq_dateTimeArray, function ($c, $d) {
        return $c <=> $d; // This comparison sorts from oldest to latest
    });

    // Create an associative array to store counts for each week
    $aq_weekCounts = array();

    // Loop through each date
    foreach ($aq_dateTimeArray as $aq_date) {
        // Extract the week and year
        $aq_weekYear = $aq_date->format('W/Y');

        // Increment the count for the week
        $aq_weekCounts[$aq_weekYear] = isset($aq_weekCounts[$aq_weekYear]) ? $aq_weekCounts[$aq_weekYear] + 1 : 1;
    }

    // Sort the array of week counts in ascending order by keys
    ksort($aq_weekCounts);

    // Get the last element of the array
    $aq_lastWeekCount = end($aq_weekCounts);
    $aq_lastWeekIndex = key($aq_weekCounts);

    if ($aq_qry % 5 == 0) {
        $hint = '';

        for ($i = 1; $i <= 4; $i++) {
            for ($j = 1; $j <= 4; $j++) {
                if ($aq_modifiedQry % 5 == $i && $aq_lastWeekCount % 5 == $j) {
                    $hint = $j . ' out of 5';
                    break 2; // Exit both loops
                }
            }
        }

        if ($hint === '') {
            $hint = $aq_lastWeekCount / 5;
        }
    } elseif ($aq_qry % 5 == 1) {
        $hint = '';
        switch ($_GET['aq_count'] % 5) {
            case 1:
                $hint = '2 out of 5';
                break;
            case 2:
                $hint = '3 out of 5';
                break;
            case 3:
                $hint = '4 out of 5';
                break;
            case 4:
                $hint = ($_GET['aq_count'] + $aq_qry) / 5;
                break;
            case 0:
                $hint = '1 out of 5';
                break;
        }
    } elseif ($aq_qry % 5 == 2) {
        $hint = '';
        switch ($_GET['aq_count'] % 5) {
            case 1:
                $hint = '3 out of 5';
                break;
            case 2:
                $hint = '4 out of 5';
                break;
            case 3:
                $hint = ($_GET['aq_count'] + $aq_qry) / 5;
                break;
            case 4:
                $hint = '1 out of 5';
                break;
            case 0:
                $hint = '2 out of 5';
                break;
        }
    } elseif ($aq_qry % 5 == 3) {
        $hint = '';
        switch ($_GET['aq_count'] % 5) {
            case 1:
                $hint = '4 out of 5';
                break;
            case 2:
                $hint = ($_GET['aq_count'] + $aq_qry) / 5;
                break;
            case 3:
                $hint = '1 out of 5';
                break;
            case 4:
                $hint = '2 out of 5';
                break;
            case 0:
                $hint = '3 out of 5';
                break;
        }
    } elseif ($aq_qry % 5 == 4) {
        $hint = '';
        switch ($_GET['aq_count'] % 5) {
            case 1:
                $hint = ($_GET['aq_count'] + $aq_qry) / 5;
                break;
            case 2:
                $hint = '1 out of 5';
                break;
            case 3:
                $hint = '2 out of 5';
                break;
            case 4:
                $hint = '3 out of 5';
                break;
            case 0:
                $hint = '4 out of 5';
                break;
        }
    }
    // if ($_GET['aq_count'] % 3 == 1) {
    //     if ($aq_modifiedQry % 3 == 1) {
    //         $hint = '1 out of 3';
    //     } elseif ($aq_modifiedQry % 3 == 2) {
    //         $hint = '2 out of 3';
    //     } else {
    //         $hint = $aq_modifiedQry  / 3;
    //     }
    // } elseif ($_GET['aq_count'] % 3 == 2) {
    //    
    //        $hint = ($_GET['aq_count'] + $aq_qry)  / 3;
    //     else {
    //         $hint = '2 out of 3';
    //     }
    // } else {
    //    
    //        $hint = '1 out of 3';
    //    
    // }
    // if ($aq_qry == 0) {
    //     if ($aq_modifiedQry % 5 == 1 && $aq_highestIndex % 5 == 1) {
    //         $hint = '1 out of 5';
    //     } elseif ($aq_modifiedQry % 5 == 2 && $aq_highestIndex % 5 == 2) {
    //         $hint = '2 out of 5';
    //     } elseif ($aq_modifiedQry % 5 == 3 && $aq_highestIndex % 5 == 3) {
    //         $hint = '3 out of 5';
    //     } elseif ($aq_modifiedQry % 5 == 4 && $aq_highestIndex % 5 == 4) {
    //         $hint = '4 out of 5';
    //     } else {
    //         $hint = $aq_highestIndex / 5;
    //     }
    // }
    // $hint = $_GET['aq_count'];
} elseif ($decodedBadge == 'BQ-#3' && !empty($_GET['bq_date_commited'])) {
    $bq_qry = $conn->query("SELECT id FROM ir_list  WHERE emp_no = '{$_GET['i']}' AND valid = 1 AND cleansed = 0 AND code_no = '{$decodedBadge}' AND WEEK(date_commited) = WEEK('{$_GET['bq_date_commited']}')")->num_rows;
    $bq_modifiedQry = $bq_qry + 1;

    $_SESSION['date_commited_array_bq'] = $_SESSION['date_commited_array_bq'] ?? [];


    // Push the new date into the array

    $bq_indexToRemove = $_GET['bq_arr_index'] - 1;

    if (isset($_SESSION['date_commited_array_bq'][$bq_indexToRemove])) {
        unset($_SESSION['date_commited_array_bq'][$bq_indexToRemove]);
    } else {
        array_splice($_SESSION['date_commited_array_bq'], $bq_indexToRemove, 0, $_GET['bq_date_commited']);
    }
    // Assuming $_SESSION['date_commited_array_bq'] is an array of date strings
    $bq_dateCommitedArray = $_SESSION['date_commited_array_bq'];

    // Convert the array of date strings into an array of DateTime objects
    $bq_dateTimeArray = [];
    foreach ($bq_dateCommitedArray as $bq_dateString) {
        $bq_dateTimeArray[] = new DateTime($bq_dateString);
    }

    // Sort the array of DateTime objects
    usort($bq_dateTimeArray, function ($c, $d) {
        return $c <=> $d; // This comparison sorts from oldest to latest
    });

    // Create an associative array to store counts for each week
    $bq_weekCounts = array();

    // Loop through each date
    foreach ($bq_dateTimeArray as $bq_date) {
        // Extract the week and year
        $bq_weekYear = $bq_date->format('W/Y');

        // Increment the count for the week
        $bq_weekCounts[$bq_weekYear] = isset($bq_weekCounts[$bq_weekYear]) ? $bq_weekCounts[$bq_weekYear] + 1 : 1;
    }

    // Sort the array of week counts in ascending order by keys
    ksort($bq_weekCounts);

    // Get the last element of the array
    $bq_lastWeekCount = end($bq_weekCounts);
    $bq_lastWeekIndex = key($bq_weekCounts);

    if ($bq_qry % 3 == 0) {
        $hint = '';

        for ($i = 1; $i <= 2; $i++) {
            for ($j = 1; $j <= 2; $j++) {
                if ($bq_modifiedQry % 3 == $i && $bq_lastWeekCount % 3 == $j) {
                    $hint = $j . ' out of 3';
                    break 2; // Exit both loops
                }
            }
        }

        if ($hint === '') {
            $hint = $bq_lastWeekCount / 3;
        }
    } elseif ($bq_qry % 3 == 1) {
        $hint = '';
        switch ($_GET['bq_count'] % 3) {
            case 1:
                $hint = '2 out of 3';
                break;
            case 2:
                $hint = ($_GET['bq_count'] + $bq_qry) / 3;
                break;
            case 0:
                $hint = '1 out of 3';
                break;
        }
    } elseif ($bq_qry % 3 == 2) {
        $hint = '';
        switch ($_GET['bq_count'] % 3) {
            case 1:
                $hint = ($_GET['bq_count'] + $bq_qry) / 3;
                break;
            case 2:
                $hint = '1 out of 3';
                break;
            case 0:
                $hint = '2 out of 3';
                break;
        }
    }
} else {
    // $qry = $conn->query("SELECT * FROM `ir_list` WHERE emp_no = '{$_GET['i']}' and cleansed = 0 and valid = 1 and code_no = '" . $decodedBadge . "'")->num_rows;
    $qry = $conn->query("SELECT offense_no FROM `ir_list` WHERE emp_no = '{$_GET['i']}' and cleansed = 0 and valid = 1 and code_no = '" . $decodedBadge . "'")->fetch_array()[0] ?? 0;
    $hint = $qry + 1;
}
// if ($qry->num_rows > 0) {
//   // Output each row of the data 
//   while ($row = $qry->fetch_assoc()) {
//     $hint = $row['violation'];
//   }
// }

// Output "no suggestion" if no hint was found or output correct values
echo $hint === 0 ? "Unregistered badge number" : $hint;
