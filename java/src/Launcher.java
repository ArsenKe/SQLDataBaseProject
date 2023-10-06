import java.io.BufferedReader;
import java.io.FileReader;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import static java.lang.Float.parseFloat;
import static java.lang.Integer.parseInt;
import static java.lang.System.exit;


public class Launcher {
	static DatabaseHelper db = new DatabaseHelper();
	public static void main(String[] args) throws SQLException {

		//Drop existing tables
		System.out.print("Drop Existing Objects...");
		db.dropAll();
		System.out.println("Completed");

		//Create tables
		System.out.print("Creating New Tables...");
		db.createTables();
		System.out.println("Completed");

		//Create Foreign Keys
		System.out.print("Creating Foreign Key Constraints...");
		db.createForeignKeys();
		System.out.println("Completed");


		//Load OnlineShop Data
		loadOnlineShop();

		//Load OnlineShop Data
		loadCategory();

		//Load OnlineShop Data
		loadOnlineShopCategory();

		//Load OnlineShop Data
		loadCustomer();

		//Load OnlineShop Data
		loadProduct();

		//Load OnlineShop Data
		loadOrders();

		//Load OnlineShop Data
		loadCart();

		//Load OnlineShop Data
		loadShipment();
	}

	//load onlineshop
	static void loadOnlineShop(){
		List<String> rows = csvReader("data\\onlineshop.csv");
		for(String row : rows){
			String[] cols = row.split(",");
			db.insertIntoOnlineShop(parseInt(cols[0]),cols[1],cols[2]);
		}
		System.out.println(rows.size() + " tuples added to onlineshop table.");
	}

	//load category
	static void loadCategory(){
		List<String> rows = csvReader("data\\category.csv");
		for(String row : rows){
			String[] cols = row.split(",");
			db.insertIntoCategory(parseInt(cols[0]),cols[1],cols[2]);
		}
		System.out.println(rows.size() + " tuples added to category table.");
	}

	//load onlineshop_category
	static void loadOnlineShopCategory(){
		List<String> rows = csvReader("data\\onlineshopcategory.csv");
		for(String row : rows){
			String[] cols = row.split(",");
			db.insertIntoOnlineShopCategory(parseInt(cols[0]), parseInt(cols[1]));
		}
		System.out.println(rows.size() + " tuples added to onlineshop_category table.");
	}

	//load customer
	static void loadCustomer() {
		List<String> rows = csvReader("data\\customer.csv");
		for(String row : rows){
			String[] cols = row.split(",",-1);
			db.insertIntoCustomer(parseInt(cols[0]), cols[1], cols[2], cols[3], cols[4], cols[5]);
		}
		System.out.println(rows.size() + " tuples added to customer table.");
	}

	//load market product
	static void loadProduct(){
		List<String> rows = csvReader("data\\product.csv");
		for(String row : rows){
			String[] cols = row.split(",");
			db.insertIntoProduct(parseInt(cols[0]),  parseInt(cols[1]), cols[2], parseFloat(cols[3]), parseFloat(cols[4]));
		}
		System.out.println(rows.size() + " tuples added to product table.");
	}

	//load market orders
	static void loadOrders(){
		List<String> rows = csvReader("data\\orders.csv");
		for(String row : rows){
			String[] cols = row.split(",");
			db.insertIntoOrders(parseInt(cols[0]), parseInt(cols[1]), cols[2], cols[3], parseFloat(cols[4]), parseFloat(cols[5]));
		}
		System.out.println(rows.size() + " tuples added to orders table.");
	}

	//load cart
	static void loadCart(){
		List<String> rows = csvReader("data\\cart.csv");
		for(String row : rows){
			String[] cols = row.split(",");
			db.insertIntoCart(parseInt(cols[0]), parseInt(cols[1]), parseInt(cols[2]), parseFloat(cols[3]), parseFloat(cols[4]));
		}
		System.out.println(rows.size() + " tuples added to cart table.");
	}

	//load market shipment
	static void loadShipment(){
		List<String> rows = csvReader("data\\shipment.csv");
		for(String row : rows){
			String[] cols = row.split(",");
			db.insertIntoShipment(parseInt(cols[0]), parseInt(cols[1]), cols[2], cols[3], cols[4], parseFloat(cols[5]), parseFloat(cols[6]));
		}
		System.out.println(rows.size() + " tuples added to shipment table.");
	}


	//read csv to List of String
	static List<String> csvReader(String pathToCsv)  {
		BufferedReader csvReader = null;
		String row; List<String> data = new ArrayList<String>(Arrays.asList());

		try {
			csvReader = new BufferedReader(new FileReader(pathToCsv));

			while ((row = csvReader.readLine()) != null) {
				 data.add(row); //= row.split(",");
			}
			csvReader.close();

		} catch (Exception e) {
			System.err.println("Error at: csvReader\n");
			System.out.println(System.getProperty("user.dir"));
			e.printStackTrace();
		}
		return data;
	}
}