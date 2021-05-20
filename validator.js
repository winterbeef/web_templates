const validator = (() => {
    let error = '';
    const MIN_LENGTH = 8;
    const MIN_CLASSES = 3;

    const num_classes = (q) => {
        let num_classes=0;
        $.each([
            new RegExp("\\d"),
            new RegExp("[a-z]"),
            new RegExp("[A-Z]"),
            new RegExp("[^A-Za-z\\d]")
        ], function(i, rgx) {
            if (rgx.test(q)) {
                num_classes++;
            }
        });
        return num_classes;
    };

    return {
        check: (p) => {
            let errors = [];
            if (p.length<MIN_LENGTH) {
                errors.push(`Password not long enough: ${p.length}/${MIN_LENGTH}.`);
            };
            if (num_classes(p)<MIN_CLASSES) {
                errors.push(`Password needs 3 of UPPER, lower, #digits, $ymbols: ${num_classes(p)}/${MIN_CLASSES}.`);
            }
            error = errors.join("\n");
            return errors.length === 0;
        },
        last_error: () => {
            return error;
        }
    }
})();
