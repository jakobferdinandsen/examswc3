package J2;

import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.InputMismatchException;
import java.util.Scanner;

/**
 * Created by jakob on 15-12-16.
 */
public class Main {
    public static void main(String[] args) {
        ArrayList<Student> studentList = new ArrayList<>();
        StudentGenerator sg = new StudentGenerator();
        Scanner scanner = new Scanner(System.in);
        System.out.println("Please enter a number[Default is 10]");
        int defaultStuds = 10;
        try {
            defaultStuds = scanner.nextInt();
        }catch(InputMismatchException e){
            System.out.println("input was not a number, using default value of 10");
        }

        for (int i = 0; i < defaultStuds; i++) {
            studentList.add(sg.genStudent());
        }
        genStudentFile(studentList);
    }

    public static void genStudentFile(ArrayList<Student> students) {
        String result = "[" + System.lineSeparator();

        for (Student student :
                students) {
            result += "{" + System.lineSeparator() + student.toJSONstring() + "}," + System.lineSeparator();
        }
        result = result.substring(0, result.length() - 2) + System.lineSeparator() + "]";


        try {
            FileWriter fw = new FileWriter("./j2_jako3952.txt");
            fw.write(result);
            fw.flush();
            fw.close();

        } catch (IOException e) {
            e.printStackTrace();
        }
    }


}
