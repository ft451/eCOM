package pl.phonegap.first;

import com.phonegap.*;
import android.os.Bundle;

public class PhonegapActivity extends DroidGap {
    /** Called when the activity is first created. */
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        /*super.init();
        KeyBoard keyboard = new KeyBoard(this, appView);
        appView.addJavascriptInterface(keyboard, "KeyBoard");*/
        super.loadUrl("file:///android_asset/www/index.html");
    }
}