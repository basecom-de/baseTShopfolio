# baseTShopfolio
Shopfolio is an interface to add a shopware shop to the Klipfolio Data Sources. Shopware provides useful information about the
shop, and they can be presented in Graphs or Dashboards via Klipfolio.

## Documentation
### Authentification

The Shopware API needs a DIgest Authentication, but as Klipfolio only offers Basic HTTP, the Plugin is used over the Frontend 
Controller.
I build in a Basic Authentication for the URL for security Issues. This Authentication checks, if the user is already logged 
in, and if not asks him to log in. The Programm checks first, if the Name exists in the Database, and then if the Name and the
Password (the Shopware API-key) belong together. A user gets the API-Key, when he generates it in the User Administration. 
When the User tried to log in wrong, he gets a message in JSON-Format that his Authenticatin is invalid or wrong.

### Controller

We use the Frontend Controller. The most important File is the IndexAction.
If you use the URL without any kind of additional Information you get any Data there is at once, else you only get the one you
called.You get the Data in an Array, if you need more then one Information in a moredimensional one. 
###
In this Method the Data is read out of the Database via SQL. Once we have all the needed Data, it is put through a 
DataTransformer and converted to the needed format (JSON or XML). If you do not decide on a Format it will automaticly be JSON.
Data that does not exist yet will not be loaded at all.

### DataTransformer

The DataTransformer is a Service that takes the Data from an Array and converts it to a different format. You can choose between JSON and XML.

### URL

The URL is generated from the Bootstrap file of the Plugin. In the event that someone calls 'Shopadresse/klip' the function onGetControllerPath starts and leads to the Controller, after the user identified himself.

### Connection with Klipfolio

You can connect with Klipfolio using the URL. It is 'shopurl/klip'. Then you have to Authenticate via Basic HTTP Authentication, and then you get the Data.

### Interfaces

All Data is provided by a function, thats starts the SQL query. Here is a List of all Data Provided: 
1. Number of orders over the last 30 days per day
2. Number of orders over the last 30 days in total
3. Number of orders the 30 days before per day
4. Number of orders the 30 days before in total
5. Number of orders today
6. Average shopping cart value in the last 30 days
7. Average shopping cart value in the 30 days before
8. Average turnover per day netto
9. Turnover development per day for the last 30 days
10. Turnover per year
11. Average Turnover per client per year
12. Turnover per Client
13. Turnover per shipping type
14. Turnover per payment type
15. Turnover total
16. Average order value
17. Average number of orders per client
18. Number of orders (divided by status and in total)
19. Number of orders with the status 'processing'
20. Sold number of pieces per day
21. Average sold number of pieces per day
22. Average price of sold Articles
23. Number of new Clients in the last 60 days
24. Number of old clients
25. Top Ten Articles per Turnover
26. Top Ten Articles per sold number of pieces
27. Top Ten Clients per Turnover
