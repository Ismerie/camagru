export default class UtilsCheck {
    static sanitize(str) {
        if (typeof str !== "string") 
            return "";
    
        let clean = str.replace(/<[^>]*>?/gm, "");
    
        clean = clean
          .replace(/&/g, "&amp;")
          .replace(/</g, "&lt;")
          .replace(/>/g, "&gt;")
          .replace(/"/g, "&quot;")
          .replace(/'/g, "&#x27;")
          .replace(/\//g, "&#x2F;");
    
        return clean.trim();
    }

    static isValidPassword(password) {
        const hasLetter = /[a-zA-Z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecialChar = /[!@#$%^&*()_\-+=]/.test(password);
      
        return password.length >= 8 && hasLetter && hasNumber && hasSpecialChar;
    }

    static isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    static isValidUsername(username) {
        return username.length >= 3 && username.length <= 10
    }
}