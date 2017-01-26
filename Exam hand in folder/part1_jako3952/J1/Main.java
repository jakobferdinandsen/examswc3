package J1;

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;

/**
 * Created by jakob on 15-12-16.
 */
public class Main {
    public static void main(String[] args) {
        ArrayList<Student> studentList = new ArrayList<>();
        StudentGenerator sg = new StudentGenerator();

        for (int i = 0; i < 10; i++) {
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
            FileWriter fw = new FileWriter("./j1_jako3952.txt");
            fw.write(result);
            fw.flush();
            fw.close();

        } catch (IOException e) {
            e.printStackTrace();
        }
    }


}
