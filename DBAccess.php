<?php
	// 接続文字列を環境変数より取得
	$connstr  = getenv("SQLAZURECONNSTR_defaultConnection");
	
    // 接続文字列を各要素へ分解
    foreach ($_SERVER as $key => $value) 
    {
        if (strpos($key, "SQLAZURECONNSTR_") !== 0) 
        {
            continue;
        }
        $hostname = preg_replace("/^.*Data Source=(.+?);.*$/", "\\1", $value);
        $database = preg_replace("/^.*Initial Catalog=(.+?);.*$/", "\\1", $value);
        $username = preg_replace("/^.*User Id=(.+?);.*$/", "\\1", $value);
        $password = preg_replace("/^.*Password=(.+?);.*$/", "\\1", $value);
        break;
    }

	$connectionOptions = array(
	    "Database" => $database,
	    "Uid" => $username,
	    "PWD" => $password
	);

	// データベースへ接続
	$conn = sqlsrv_connect($hostname, $connectionOptions);
	$sql= "SELECT * FROM [dbo].[test_table]";
	$getResults= sqlsrv_query($conn, $sql);
	echo ("<p>Reading data from SQL Database.</p>" . PHP_EOL);
	
	// テーブルからデータ取得
	if ($getResults == FALSE)
		echo (sqlsrv_errors());
	while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
		echo ($row['ID'] . " " . $row['Name'] . " " . $row['Value'] . "<br>" . PHP_EOL);
	}
	
	// 解放処理
	sqlsrv_free_stmt($getResults);
?>
