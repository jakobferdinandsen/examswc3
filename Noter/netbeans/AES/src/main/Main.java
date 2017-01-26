package main;

import java.io.*;
import java.util.ArrayList;
import java.util.Scanner;

public class Main {

    public static void main(String[] args) throws IOException {
        Scanner scanner = new Scanner(System.in);
        System.out.println("Path to file:");

        String path = "Test";

        File file = new File(path);
        System.out.println(file.getAbsolutePath());
        System.out.println("Do you want to decrypt[D] or encrypt[E]?");

        String result = scanner.next();

        if (result.equals("E")) {
            System.out.println("Encryption key:");
            AESImpl.setKeyValue(scanner.next());

            FileReader fr = new FileReader(file);
            BufferedReader textReader = new BufferedReader(fr);
            String textData = "";
            String aLine;

            while ((aLine = textReader.readLine()) != null) {
                textData = textData + aLine+"\n";
            }
            textReader.close();

            String encrypted = "";

            try {
                encrypted = AESImpl.encrypt(textData);
            } catch (Exception e) {
                System.out.println(e.getMessage());
                main(null);
            }

            PrintWriter pw = new PrintWriter(file);

            pw.print(encrypted);
            pw.close();

        } else if (result.equals("D")) {

            System.out.println("Decryption key:");
            AESImpl.setKeyValue(scanner.next());

            FileReader fr = new FileReader(file);
            BufferedReader textReader = new BufferedReader(fr);
            String textData = "";
            String aLine;

            while ((aLine = textReader.readLine()) != null) {
                textData = textData + aLine;
            }
            textReader.close();

            String decrypted = "";

            try {
                decrypted = AESImpl.decrypt(textData);
            } catch (Exception e) {
                System.out.println(e.getMessage());
                main(null);
            }

            PrintWriter pw = new PrintWriter(file);

            pw.print(decrypted);
            pw.close();

        } else {
            System.out.println("Available options are decrypt[D] and encrypt[E]");
            main(null);
        }
    }
}
