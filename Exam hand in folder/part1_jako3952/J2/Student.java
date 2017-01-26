package J2;

/**
 * Created by jakob on 15-12-16.
 */
public class Student {
    private int id;
    private String first_name;
    private String last_name;
    private String cpr;
    private String date_of_birth;
    private String email;
    private Address address;
    private String institution;

    public Student(int id, String first_name, String last_name, String cpr, String date_of_birth, String email, Address address, String institution) {
        this.id = id;
        this.first_name = first_name;
        this.last_name = last_name;
        this.cpr = cpr;
        this.date_of_birth = date_of_birth;
        this.email = email;
        this.address = address;
        this.institution = institution;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getFirst_name() {
        return first_name;
    }

    public void setFirst_name(String first_name) {
        this.first_name = first_name;
    }

    public String getLast_name() {
        return last_name;
    }

    public void setLast_name(String last_name) {
        this.last_name = last_name;
    }

    public String getCpr() {
        return cpr;
    }

    public void setCpr(String cpr) {
        this.cpr = cpr;
    }

    public String getDate_of_birth() {
        return date_of_birth;
    }

    public void setDate_of_birth(String date_of_birth) {
        this.date_of_birth = date_of_birth;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public Address getAddress() {
        return address;
    }

    public void setAddress(Address address) {
        this.address = address;
    }

    public String getInstitution() {
        return institution;
    }

    public void setInstitution(String institution) {
        this.institution = institution;
    }

    public String toJSONstring() {
        String result = "";
        result += "\t\"id\":"+id+","+System.lineSeparator();
        result += "\t\"first_name\":\""+first_name+"\","+System.lineSeparator();
        result += "\t\"last_name\":\""+last_name+"\","+System.lineSeparator();
        result += "\t\"CPR\":\""+cpr+"\","+System.lineSeparator();
        result += "\t\"date_of_birth\":\""+date_of_birth+"\","+System.lineSeparator();
        result += "\t\"email\":\""+email+"\","+System.lineSeparator();

        result += "\t\"address\":{"+System.lineSeparator();

        result += "\t\t\"street\":\""+address.getStreet()+"\","+System.lineSeparator();
        result += "\t\t\"number\":\""+address.getNumber()+"\","+System.lineSeparator();
        result += "\t\t\"zip_code\":\""+address.getZip()+"\","+System.lineSeparator();
        result += "\t\t\"city\":\""+address.getCity()+"\""+System.lineSeparator();

        result += "\t},"+System.lineSeparator();

        result += "\t\"institution_name\":\""+institution+"\""+System.lineSeparator();


        return result;
    }
}
