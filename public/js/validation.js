class Validation {
    #ErrorList = {};
    #item;
    addInput(input, call,) {
        this.input = input;
        this.call = call;
        this.#item = this.#ErrorList[input.attr("id")] = [];
        return this
    }

    required() {
        if (this.input.val().length === 0) {
            this.#item.push(`فیلد ${this.call} الزامی می باشد `);
        }
        return this;
    }


    matchRegex(pattern) {
        let regex = new RegExp(pattern)
        if (!regex.test(this.input.val())) {
            this.#item.push(`فیلد ${this.call} معتبر نیست `);
        }
        return this;
    }

    maxlength(max) {
        if (this.input.val().length > max) {
            this.#item.push(`فیلد ${this.call} باید کمتر از ${max} کارکتر باشد `);
        }
        return this;
    }

    minlength(min) {
        if (this.input.val().length < min) {
            this.#item.push(`فیلد ${this.call} باید بیشتر از ${min} کارکتر باشد `);
        }
        return this;
    }

    errorList() {
        for (let key of Object.keys(this.#ErrorList)) {
            if (this.#ErrorList[key].length === 0) {
                delete this.#ErrorList[key];
            }
        }
        return this.#ErrorList;
    }

    noneError() {
        return Object.keys(this.#ErrorList).length === 0;
    }

}
