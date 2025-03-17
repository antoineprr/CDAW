<?php

User::addSqlQuery('USER_LIST',
	'SELECT * FROM USERS ORDER BY name');

User::addSqlQuery('USER_GET_WITH_ID',
	'SELECT * FROM USERS WHERE id=:id');

User::addSqlQuery('USER_UPDATE',
	'UPDATE USERS SET name=:name, email=:email WHERE id=:id');

User::addSqlQuery('USER_GET_WITH_LOGIN',
	'SELECT * FROM USERS WHERE USER_LOGIN=:login');

User::addSqlQuery('USER_CREATE',
	'INSERT INTO USERS (USER_ID, USER_LOGIN, USER_EMAIL, USER_ROLE, USER_PWD, USER_NAME, USER_SURNAME) VALUES (NULL, :login, :email, :role, :pwd, :name, :surname)');

User::addSqlQuery('USER_CONNECT',
	'SELECT * FROM USERS WHERE USER_LOGIN=:login and USER_PWD=:password');