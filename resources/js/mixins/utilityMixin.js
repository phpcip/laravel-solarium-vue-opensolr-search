export default {
    methods:{
        decoder (str, maxLen) {
            var textArea = document.createElement('textarea');
            textArea.innerHTML = str;
            return (textArea.value.length > maxLen) ? textArea.value.substr(0,maxLen) + "..." : textArea.value;
        },
        in_array(needle, haystack) {
            var length = haystack.length;
            for(var i = 0; i < length; i++) {
                if(haystack[i] === needle) return i;
            }
            return false;
        },
        remove_element(needle, haystack) {
            var index = haystack.indexOf(needle);
            if (index > -1) {
                haystack.splice(index, 1);
            }
            return haystack;
        }
    }
}