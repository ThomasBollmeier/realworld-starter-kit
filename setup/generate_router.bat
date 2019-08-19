@echo off
php ..\vendor\tbollmeier\webappfound\bin\router_generate.php ^
	--namespace=tbollmeier\realworld\backend\routing ^
	--name=Router ^
	--base-alias=BaseRouter ^
	-o ..\src\backend\routing\Router.php ^
	.\controllers