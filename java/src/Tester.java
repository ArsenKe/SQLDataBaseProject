public class Tester {

	public static void main(String[] args) {
		DatabaseHelper dbHelper=new DatabaseHelper();
		
		//1
		dbHelper.insertIntoOnlineShop(2, "Test Company", "www.example.com");
		
		//2
//		ArrayList<Integer>  result=dbHelper.selectPersonIdsFromPerson();
//
//		for(int id:result) {
//			System.out.println(id);
//		}
		
		//TODO: your task: write a function which inserts to table professor  (Method InsertIntoProfessor in DatabaseHelper)

		
		//TODO: your task wirte a function which selects the professor id (Method selectProfessorIdFromProfessor in DatabaseHelper)
		

		dbHelper.close();
	}

}
