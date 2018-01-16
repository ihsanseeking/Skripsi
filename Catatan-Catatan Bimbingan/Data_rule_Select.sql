<select statement>::=

SELECT

[<select option> [<select option>...]]

{* | <select list>}

	[
		FROM <table reference> [{, <table reference>}...]
			[WHERE <expression> [{<operator> <expression>}...]]
			[GROUP BY <group by definition>]
			[HAVING <expression> [{<operator> <expression>}...]]
			[ORDER BY <order by definition>]
			[LIMIT [<offset>,] <row count>]
	]
	
	
<select option>::=
{ALL | DISTINCT | DISTINCTROW}

<select list>::=
{<column name> | <expression>} [[AS] <alias>]
[{, {<column name> | <expression>} [[AS] <alias>]}...]

<table reference>::=
<table name> [[AS] <alias

<group by definition>::=
<column name> [ASC | DESC]
[{, <column name> [ASC | DESC]}...]
[WITH ROLLUP]

<order by definition>::=
<column name> [ASC | DESC]
[{, <column name> [ASC | DESC]}...]

===================================================================
---------------
SELECT {*} [FROM <table reference>]
>>
SELECT * FROM CDs;
--------------
SELECT {<select list>} 
[FROM <table reference>]
>>
SELECT CDID, CDName, Category
FROM CDs;
--------------------
SELECT {<column name>} [[AS] <alias>]} 
[FROM <table reference>]
>>
SELECT EmpFN AS ‘First Name’, EmpLN AS ‘Last Name’
FROM Employees;
-------------------------------------
Using Expressions in a SELECT Statement
<<
SELECT {<expression>} [[AS] <alias>]} 
[FROM <table reference>]
>>
SELECT CDName, InStock+OnOrder AS Total
FROM CDs;

SELECT CDName, InStock+OnOrder-Reserved AS Total
FROM CDs;
-----------------------------
Using a SELECT Statement to Display Values
>>
SELECT 1+3, ‘CD Inventory’, NOW() AS ‘Date/Time’;
==
+-----+--------------+---------------------+
| 1+3 | CD Inventory | Date/Time           |
+-----+--------------+---------------------+
|   4 | CD Inventory | 2004-08-24 11:39:40 |
+-----+--------------+---------------------+
1 row in set (0.00 sec)
-----------------------------------------------
The SELECT Statement Options
<<
<select option>::=
{ALL | DISTINCT | DISTINCTROW}
| HIGH_PRIORITY
| {SQL_BIG_RESULT | SQL_SMALL_RESULT}
| SQL_BUFFER_RESULT
| {SQL_CACHE | SQL_NO_CACHE}
| SQL_CALC_FOUND_ROWS
| STRAIGHT_JOIN
~~
[
1.Option 
2.Description

1.ALL DISTINCT DISTINCTROW 
2.The ALL option specifies that a query should return
all rows, even if there are duplicate rows. The DISTINCT
and DISTINCTROW options, which have the
same meaning in MySQL, specify that duplicate rows
should not be included in the result set. If neither
option is specified, ALL is assumed.

1.HIGH_PRIORITY 
2.The HIGH_PRIORITY option prioritizes the SELECT
statement over statements that write data to the target
table. Use this option only for SELECT statements
that you know will execute quickly.

1.SQL_BIG_RESULT SQL_SMALL_RESULT 
2.The SQL_BIG_RESULT option informs the MySQL
optimizer that the result set will include a large number
of rows, which helps the optimizer to process the
query more efficiently. The SQL_SMALL_RESULT
option informs the MySQL optimizer that the result
set will include a small number of rows.

1.SQL_BUFFER_RESULT 
2.The SQL_BUFFER_RESULT option tells MySQL to
place the query results in a temporary table in order
to release table locks sooner than they would normally
be released. This option is particularly useful for
large result sets that take a long time to return to the
client.

1.SQL_CACHE SQL_NO_CACHE 
2.The SQL_CACHE option tells MySQL to cache the
query results if the cache is operating in demand
mode. The SQL_NO_CACHE option tells MySQL not to
cache the query results.

1.SQL_CALC_FOUND_ROWS 
2.You use the SQL_CALC_FOUND_ROWS option in conjunction
with the LIMIT clause. The option specifies
what the row count of a result set would be if the
LIMIT clause were not used.

1.STRAIGHT_JOIN 
2.You use the STRAIGHT_JOIN option when joining
tables in a SELECT statement. The option tells the
optimizer to join the tables in the order specified in
the FROM clause. You should use this option to speed
up a query if you think that the optimizer is not joining
the tables efficiently.
]
~~
SELECT [<select option>] {<select list>} 
[FROM <table reference>]
>>
<select option>::=
{ALL | DISTINCT}
>>
SELECT ALL Department, Category
FROM CDs;
/
SELECT DISTINCT Department, Category
FROM CDs;
--
<select option>::=
{ALL | DISTINCT} | HIGH_PRIORITY
>>
SELECT DISTINCT HIGH_PRIORITY Department, Category
FROM CDs;
----------------------------------------------
The Optional Clauses of a SELECT
Statement
++++++++++++++++
The WHERE Clause
==
WHERE <expression> [{<operator> <expression>}...]
>>
SELECT CDName, InStock+OnOrder-Reserved AS Available
FROM CDs
WHERE Category=’Blues’;
/
SELECT CDName, InStock+OnOrder-Reserved AS Available
FROM CDs
WHERE Category=’Blues’ AND (InStock+OnOrder-Reserved)>30;
/
SELECT CDName, Category, InStock+OnOrder-Reserved AS Available
FROM CDs
WHERE (Category=’Blues’ OR Category=’Jazz’)
AND (InStock+OnOrder-Reserved)>20;
++++++++++++++++
The GROUP BY Clause
==
GROUP BY <group by definition>
<group by definition>::=
<column name> [ASC | DESC]
[{, <column name> [ASC | DESC]}...]
[WITH ROLLUP]
>>
SELECT Category, COUNT(*) AS Total
FROM CDs
WHERE Department=’Popular’
GROUP BY Category;
/
SELECT Department, Category, COUNT(*) AS Total
FROM CDs
GROUP BY Department, Category;
/
SELECT Department, Category, COUNT(*) AS Total
FROM CDs
GROUP BY Department, Category WITH ROLLUP;
+++++++++++++++
The HAVING Clause
==
HAVING <expression> [{<operator> <expression>}...]
>>
SELECT Category, COUNT(*) AS Total
FROM CDs
WHERE Department=’Popular’
GROUP BY Category
HAVING Total<3;
++++++++++++++++
The ORDER BY Clause
==
ORDER BY <order by definition>
<order by definition>::=
<column name> [ASC | DESC]
[{, <column name> [ASC | DESC]}...]
>>
SELECT CDName, InStock, OnOrder
FROM CDs
WHERE InStock>20
ORDER BY CDName DESC;
/
SELECT Department, Category, CDName
FROM CDs
WHERE (InStock+OnOrder-Reserved)<15
ORDER BY Department DESC, Category ASC;
++++++++++++
The LIMIT Clause
==
LIMIT [<offset>,] <row count>
>>
SELECT CDID, CDName, InStock
FROM CDs
WHERE Department=’Classical’
ORDER BY CDID DESC
LIMIT 4;
/
SELECT CDID, CDName, InStock
FROM CDs
WHERE Department=’Classical’
ORDER BY CDID DESC
LIMIT 3,4;

======================================

Summary

Create SELECT statements that retrieve all columns and all rows from a table
❑ Create SELECT statements that retrieve specific columns from a table
❑ Assign aliases to column names
❑ Use expressions in the SELECT clauses of your SELECT statements
❑ Use SELECT statements to create variables that can be used in later SELECT statements
❑ Create SELECT statements that return information that is not based on data in a table
❑ Add options to your SELECT statements
❑ Add WHERE clauses to your SELECT statements that determine which rows the statements
would return
❑ Add GROUP BY clauses to your SELECT statements to generate summary data
❑ Add HAVING clauses to your SELECT statements to refine the results returned by summarized
data
❑ Add ORDER BY clauses to your SELECT statements to sort the rows returned by your statements
❑ Add LIMIT clauses to your SELECT statement to limit the number of rows returned by the
statement
