import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.URL;
import java.net.URLEncoder;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.json.JSONObject;
import org.json.XML;

public class SearchServlet extends HttpServlet{
	public void doGet (HttpServletRequest request, HttpServletResponse response) 
			throws IOException, ServletException{
		 response.setContentType("application/json; charset=utf-8");
		 PrintWriter out = response.getWriter();
		String comname=request.getParameter("input");
		String temp="http://default-environment-gaujpmxbpp.elasticbeanstalk.com/?input=";
			temp=temp+comname;
			try
			{URL url=new URL(temp);
			BufferedReader buf = new BufferedReader(new InputStreamReader(url.openStream()));
			String line=null;
			String xmlContent=null;
			while ((line = buf.readLine())!=null) {xmlContent += line; }
			JSONObject jsonObject = XML.toJSONObject(xmlContent);
			out.print(jsonObject.toString());
			}catch(Exception e){}
	}
}
