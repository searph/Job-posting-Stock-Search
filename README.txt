# Job-posting-Stock-Search
1.The html file is used to create the home page where users can search the stock information and news by entering the company symbol
2.In the html file, it uses AJAX(XMLHttpRequst) to call the JavaServlet file which is located on Tomcat server.
3.The JavaServlet will call the php file which is on AWS.
4.The php file calls Yahoo Finance API and gets the return data. Then it will retrieve the useful information from the original return data and create the useful data in XML format.
5.The php file returns the useful data to JavaServlet and JavaServelt will change the format from XML to JSON. Then it will return the JSON data to the callback object in XMLHttpRequst.
6.The return data will be displayed by HTML and CSS.

The JavaServlet file must be compiled first on Tomcat.
