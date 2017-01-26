package J3;

import java.util.ArrayList;
import java.util.Random;

/**
 * Created by jakob on 15-12-16.
 */
public class StudentGenerator {
    private String[] firstNameList;
    private String[] lastNameList;
    private String[] schoolsList;
    private String[] streetsList;
    private String[] zipCityList;
    private String[] streetNumberFixes;
    private int count;
    private ArrayList<String> usedCPR;

    public Student genStudent() {
        Random random = new Random();
        int id = count++;

        int randIndex = random.nextInt(firstNameList.length);
        String firstName = firstNameList[randIndex];

        randIndex = random.nextInt(lastNameList.length);
        String lastName = lastNameList[randIndex];

        String cpr = randCPR();

        String dob = genDob(cpr);

        String email = genEmail(firstName, lastName, id);

        randIndex = random.nextInt(streetsList.length);
        String street = streetsList[randIndex];

        String number = genStreetNum();

        randIndex = random.nextInt(zipCityList.length);
        String zip = zipCityList[randIndex].split(" ")[0];
        String city = zipCityList[randIndex].split(" ")[1];

        randIndex = random.nextInt(schoolsList.length);
        String institution = schoolsList[randIndex];

        Address address = new Address(street, number, zip, city);
        Student result = new Student(id, firstName, lastName, cpr, dob, email, address, institution);
        return result;
    }

    public String randCPR() {
        String cpr = genCPR();
        while (checkCPR(cpr)) {
            cpr = genCPR();
        }
        usedCPR.add(cpr);
        return cpr;
    }

    public String genCPR() {
        String cpr = "";
        Random random = new Random();

        cpr += random.nextInt(4);
        cpr += random.nextInt(10);

        int month = random.nextInt(2);
        cpr += month;
        if (month == 1) {
            cpr += random.nextInt(3);
        } else {
            cpr += random.nextInt(10);
        }
        if (random.nextInt(20) >= 10) {
            cpr += "0";
            cpr += random.nextInt(10);
        } else {
            cpr += (random.nextInt(5) + 5);
            cpr += random.nextInt(10);
        }

        for (int i = 0; i < 4; i++) {
            cpr += random.nextInt(10);
        }
        return cpr;
    }

    public String genDob(String cpr){
        String result = "";
        String year = cpr.substring(4,6);
        if (Integer.parseInt(year) > 9){
            year = 19 + year;
        }else{
            year = 20 + year;
        }
        result = year + "-" + cpr.substring(2, 4) + "-" + cpr.substring(0, 2);

        return result;
    }

    public boolean checkCPR(String cpr) {
        boolean result = false;
        for (String listCpr : usedCPR) {
            if (listCpr.equals(cpr)) {
                return true;
            }
        }
        return result;
    }

    public String genEmail(String firstname, String lastname, int id) {
        return firstname + lastname + id + "@gmail.com";
    }

    public String genStreetNum() {
        String result = "";
        Random random = new Random();
        int num = random.nextInt(500);
        String fix = streetNumberFixes[random.nextInt(streetNumberFixes.length)];
        if (fix.isEmpty()) {
            result = num + "";
        } else {
            int level = random.nextInt(30);
            result = num + " " + level + ". " + fix;
        }
        return result;
    }


    public StudentGenerator() {
        this.usedCPR = new ArrayList<>();
        this.count = 0;
        this.firstNameList = new String[]{
                "Emma",
                "Ida",
                "Clara",
                "Laura",
                "Isabella",
                "Sofia",
                "Sofie",
                "Anna",
                "Mathilde",
                "Freja",
                "William",
                "Oliver",
                "Noah",
                "Emil",
                "Bob",
                "Alice",
                "Eve"
        };

        this.lastNameList = new String[]{
                "Andersen",
                "Andersson",
                "Andreasen",
                "Andreassen",
                "Andresen",
                "Asmussen",
                "Bach",
                "Bak",
                "Bang",
                "Bech",
                "Beck",
                "Bendtsen",
                "Berg",
                "Bertelsen",
                "Berthelsen",
                "Bisgård",
                "Bisgaard",
                "Bjerre",
                "Bjerregård",
                "Bjerregaard",
                "Bonde"
        };

        this.schoolsList = new String[]{
                "EA Kolding",
                "EA MidtVest",
                "Erhvervsakademi Aarhus",
                "Erhvervsakademi Dania",
                "Erhvervsakademi Sjælland",
                "Erhvervsakademi SydVest",
                "Erhvervsakademiet Copenhagen Business Academy 101605",
                "Erhvervsakademiet Lillebælt",
                "KEA - Københavns Erhvervsakademi",
                "Smart Learning"
        };

        this.streetsList = new String[]{
                "Abildhøj",
                "Abrikosvej",
                "Adelsvej",
                "Ageren",
                "Agertoften",
                "Agervej",
                "Ahornvej",
                "Albanivej",
                "Alfehøjen",
                "Amtmandsstien",
                "Anemonevej",
                "Ankervej",
                "Anlægsvej",
                "Anneksvej",
                "Apotekerstræde",
                "Appenæs Bygade",
                "Appenæshoved",
                "Appenæs Åvej",
                "Askevej",
                "Askevænget",
                "Askovvej",
                "Assensvej",
                "Attebjergvej"
        };

        this.zipCityList = new String[]{
                "1050 København K",
                "1051 København K",
                "1052 København K",
                "1053 København K",
                "1054 København K",
                "1055 København K",
                "1056 København K",
                "1057 København K",
                "1058 København K",
                "1059 København K",
                "1060 København K",
                "1061 København K",
                "7742 Vesløs",
                "7752 Snedsted",
                "7755 Bedsted Thy",
                "7760 Hurup Thy",
                "7770 Vestervig",
                "7790 Thyholm",
                "7800 Skive",
                "7830 Vinderup",
                "7840 Højslev",
                "7850 Stoholm Jyll"
        };

        this.streetNumberFixes = new String[]{
                "th.",
                "tv.",
                "",
                "",
                "",
                "",
                "",
                "",
                ""
        };
    }
}
