package T2;

/**
 * Created by jakob on 22-08-16.
 */

import sun.misc.BASE64Decoder;
import sun.misc.BASE64Encoder;

import javax.crypto.Cipher;
import javax.crypto.spec.SecretKeySpec;
import java.security.Key;

public class AESImpl {

    private static final String ALGO = "AES";
    private static byte[] keyValue =
            new byte[]{'D', 'a', 'n', 'm', 'a', 'r', 'k',
                    'V', 'i', 'n', 'd', 'e', 'r', 'G', 'u', 'l'};

    public static String encrypt(String Data) throws Exception {
        Key key = new SecretKeySpec(keyValue, ALGO);
        Cipher c = Cipher.getInstance(ALGO);
        c.init(Cipher.ENCRYPT_MODE, key);
        byte[] encVal = c.doFinal(Data.getBytes());
        String encryptedValue = new BASE64Encoder().encode(encVal);
        return encryptedValue;
    }

    public static String decrypt(String encryptedData) throws Exception {
        Key key = new SecretKeySpec(keyValue, ALGO);
        Cipher c = Cipher.getInstance(ALGO);
        c.init(Cipher.DECRYPT_MODE, key);
        byte[] decordedValue = new BASE64Decoder().decodeBuffer(encryptedData);
        byte[] decValue = c.doFinal(decordedValue);
        String decryptedValue = new String(decValue);
        return decryptedValue;
    }

    private static Key generateKey() throws Exception {
        Key key = new SecretKeySpec(keyValue, ALGO);
        return key;
    }

    public static void setKeyValue(String val) {
        byte[] keyVal = new byte[val.length()];
        for (int i = 0; i < val.length(); i++) {
            keyVal[i] = (byte) val.toCharArray()[i];
        }
        keyValue = keyVal;
    }
}

