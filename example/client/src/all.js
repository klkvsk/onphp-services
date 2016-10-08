var __extends = (this && this.__extends) || function (d, b) {
    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
    function __() { this.constructor = d; }
    d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
};
define("typescript-core/src/Primitives/PrimitiveImportError", ["require", "exports"], function (require, exports) {
    "use strict";
    (function (PrimitiveImportError) {
        PrimitiveImportError[PrimitiveImportError["WRONG"] = 1] = "WRONG";
        PrimitiveImportError[PrimitiveImportError["MISSING"] = 2] = "MISSING";
    })(exports.PrimitiveImportError || (exports.PrimitiveImportError = {}));
    var PrimitiveImportError = exports.PrimitiveImportError;
    var b = PrimitiveImportError.MISSING;
});
define("typescript-core/src/Primitives/PrimitiveArray", ["require", "exports", "typescript-core/src/Primitives/Primitive", "typescript-core/src/Primitives/PrimitiveImportError"], function (require, exports, Primitive_1, PrimitiveImportError_1) {
    "use strict";
    var PrimitiveArray = (function (_super) {
        __extends(PrimitiveArray, _super);
        function PrimitiveArray() {
            _super.apply(this, arguments);
        }
        PrimitiveArray.prototype.setPrimitive = function (primitive) {
            this.primitive = primitive;
            return this;
        };
        PrimitiveArray.prototype.getPrimitive = function () {
            return this.primitive;
        };
        PrimitiveArray.prototype.importValue = function (data) {
            this.clean();
            if (!Array.isArray(data)) {
                this.error = PrimitiveImportError_1.PrimitiveImportError.WRONG;
                return false;
            }
            this.value = new Array(data.length);
            for (var i = 0; i < data.length; i++) {
                this.primitive.clean();
                this.primitive.importValue(data[i]);
                var error = this.primitive.getError();
                var value = this.primitive.getValue();
                if (error) {
                    this.subErrors[i] = error;
                }
                this.value[i] = value;
            }
        };
        PrimitiveArray.prototype.clean = function () {
            _super.prototype.clean.call(this);
            this.subErrors = [];
        };
        PrimitiveArray.prototype.getSubErrors = function () {
            return this.subErrors;
        };
        return PrimitiveArray;
    }(Primitive_1.Primitive));
    exports.PrimitiveArray = PrimitiveArray;
});
define("typescript-core/src/Primitives/Primitive", ["require", "exports", "typescript-core/src/Primitives/PrimitiveImportError", "typescript-core/src/Primitives/PrimitiveArray"], function (require, exports, PrimitiveImportError_2, PrimitiveArray_1) {
    "use strict";
    var Primitive = (function () {
        function Primitive() {
            this.required = false;
        }
        Primitive.prototype.clean = function () {
            this.value = null;
            this.error = null;
        };
        Primitive.prototype.getValue = function () {
            if (this.error) {
                return null;
            }
            return this.value;
        };
        Primitive.prototype.getError = function () {
            return this.error;
        };
        Primitive.prototype.setRequired = function (required) {
            this.required = required;
            return this;
        };
        Primitive.prototype.checkImportResult = function () {
            if (this.required && !this.value) {
                this.error = PrimitiveImportError_2.PrimitiveImportError.MISSING;
                return false;
            }
            return true;
        };
        Primitive.prototype.arrayOf = function () {
            var arrayPrm = new PrimitiveArray_1.PrimitiveArray();
            arrayPrm.setPrimitive(this);
            arrayPrm.setRequired(this.required);
            return arrayPrm;
        };
        return Primitive;
    }());
    exports.Primitive = Primitive;
});
define("typescript-core/src/Primitives/PrimitiveBoolean", ["require", "exports", "typescript-core/src/Primitives/Primitive"], function (require, exports, Primitive_2) {
    "use strict";
    var PrimitiveBoolean = (function (_super) {
        __extends(PrimitiveBoolean, _super);
        function PrimitiveBoolean() {
            _super.apply(this, arguments);
        }
        PrimitiveBoolean.prototype.importValue = function (data) {
            this.value = !!data;
            return this.checkImportResult();
        };
        return PrimitiveBoolean;
    }(Primitive_2.Primitive));
    exports.PrimitiveBoolean = PrimitiveBoolean;
});
define("typescript-core/src/Primitives/PrimitiveInteger", ["require", "exports", "typescript-core/src/Primitives/Primitive"], function (require, exports, Primitive_3) {
    "use strict";
    var PrimitiveInteger = (function (_super) {
        __extends(PrimitiveInteger, _super);
        function PrimitiveInteger() {
            _super.apply(this, arguments);
            this.min = null;
            this.max = null;
        }
        PrimitiveInteger.prototype.importValue = function (data) {
            this.value = data | 0;
            return this.checkImportResult();
        };
        PrimitiveInteger.prototype.setMin = function (min) {
            this.min = min;
            return this;
        };
        PrimitiveInteger.prototype.getMin = function () {
            return this.min;
        };
        PrimitiveInteger.prototype.setMax = function (max) {
            this.max = max;
            return this;
        };
        PrimitiveInteger.prototype.getMax = function () {
            return this.max;
        };
        PrimitiveInteger.prototype.checkImportResult = function () {
            return _super.prototype.checkImportResult.call(this)
                && (this.getMin() !== null && this.getValue() < this.getMin())
                && (this.getMax() !== null && this.getValue() > this.getMax());
        };
        return PrimitiveInteger;
    }(Primitive_3.Primitive));
    exports.PrimitiveInteger = PrimitiveInteger;
});
define("typescript-core/src/Services/ServiceActionReturn", ["require", "exports"], function (require, exports) {
    "use strict";
    var ServiceActionReturn = (function () {
        function ServiceActionReturn(primitive) {
            this.primitive = primitive;
        }
        return ServiceActionReturn;
    }());
    exports.ServiceActionReturn = ServiceActionReturn;
});
define("typescript-core/src/Common", ["require", "exports"], function (require, exports) {
    "use strict";
});
define("typescript-core/src/Primitives/PrimitiveString", ["require", "exports", "typescript-core/src/Primitives/Primitive"], function (require, exports, Primitive_4) {
    "use strict";
    var PrimitiveString = (function (_super) {
        __extends(PrimitiveString, _super);
        function PrimitiveString() {
            _super.apply(this, arguments);
        }
        PrimitiveString.prototype.importValue = function (data) {
            this.value = "" + data;
            return this.checkImportResult();
        };
        return PrimitiveString;
    }(Primitive_4.Primitive));
    exports.PrimitiveString = PrimitiveString;
});
define("typescript-core/src/Forms/Form", ["require", "exports", "typescript-core/src/Primitives/PrimitiveString"], function (require, exports, PrimitiveString_1) {
    "use strict";
    var PrimitiveFactory = (function () {
        function PrimitiveFactory() {
        }
        PrimitiveFactory.prototype.test = function () {
            return new PrimitiveString_1.PrimitiveString();
        };
        return PrimitiveFactory;
    }());
    exports.PrimitiveFactory = PrimitiveFactory;
    var Form = (function () {
        function Form(primitives) {
            if (primitives) {
                for (var name_1 in primitives) {
                    this.add(name_1, primitives[name_1]);
                }
            }
        }
        Form.prototype.add = function (name, primitive) {
            this.primitives[name] = primitive;
            return this;
        };
        Form.prototype.clean = function () {
            for (var p in this.primitives) {
                this.primitives[p].clean();
            }
            return this;
        };
        Form.prototype.importData = function (data) {
            this.clean();
            var result = true;
            for (var name_2 in this.primitives) {
                if (data.hasOwnProperty(name_2)) {
                    result = result && this.primitives[name_2].importValue(data[name_2]);
                }
            }
            return result;
        };
        Form.prototype.getErrors = function () {
            var errors = {};
            for (var name_3 in this.primitives) {
                if (this.primitives[name_3].getError()) {
                    errors[name_3] = this.primitives[name_3].getError();
                }
            }
            return errors;
        };
        Form.prototype.getValue = function (name) {
            return this.primitives[name].getValue();
        };
        Form.prototype.exportData = function () {
            var data = {};
            for (var name_4 in this.primitives) {
                data[name_4] = this.primitives[name_4].getValue();
            }
            return data;
        };
        return Form;
    }());
    exports.Form = Form;
});
define("typescript-core/src/Services/ServiceActionParams", ["require", "exports"], function (require, exports) {
    "use strict";
    var ServiceActionParams = (function () {
        function ServiceActionParams(route, query, body) {
            this.route = route;
            this.query = query;
            this.body = body;
        }
        return ServiceActionParams;
    }());
    exports.ServiceActionParams = ServiceActionParams;
});
define("typescript-core/src/Services/IServiceConnector", ["require", "exports"], function (require, exports) {
    "use strict";
});
define("typescript-core/src/Forms/FormValidationError", ["require", "exports"], function (require, exports) {
    "use strict";
    var FormValidationError = (function (_super) {
        __extends(FormValidationError, _super);
        function FormValidationError(errors) {
            _super.call(this);
            this.errors = errors;
        }
        FormValidationError.prototype.toString = function () {
            return JSON.stringify(this.errors);
        };
        return FormValidationError;
    }(Error));
    exports.FormValidationError = FormValidationError;
});
define("typescript-core/src/Services/ServiceAction", ["require", "exports", "typescript-core/src/Forms/FormValidationError"], function (require, exports, FormValidationError_1) {
    "use strict";
    var ServiceAction = (function () {
        function ServiceAction(httpMethod, httpPath, params, returns) {
            this.httpMethod = httpMethod;
            this.httpPath = httpPath;
            this.params = params;
            this.returns = returns;
        }
        ServiceAction.prototype.call = function (connector, params) {
            var errors = {};
            for (var _i = 0, _a = [this.params.route, this.params.query, this.params.body]; _i < _a.length; _i++) {
                var p = _a[_i];
                if (!p) {
                    continue;
                }
                if (!p.importData(params)) {
                    var e = p.getErrors();
                    for (var name_5 in e) {
                        errors[name_5] = e[name_5];
                    }
                }
            }
            if (Object.keys(errors).length > 0) {
                throw new FormValidationError_1.FormValidationError(errors);
            }
            // put route parameters into :placeholders
            var httpPath = this.httpPath.replace(/:([a-z][a-z0-9_]+)/ig, function (_, placeholderName) {
                if (!this.params.route) {
                    return '';
                }
                var value = this.params.route.getValue(placeholderName);
                return value ? value.toString() : '';
            });
            var request = {
                httpMethod: this.httpMethod,
                httpPath: httpPath,
                query: this.params.query.exportData(),
                body: this.params.body.exportData()
            };
            return connector.doRequest(request);
        };
        return ServiceAction;
    }());
    exports.ServiceAction = ServiceAction;
});
define("example/out-client/Services/TestService", ["require", "exports", "./typescript-core/src/Services/BaseService", "typescript-core/src/Primitives/PrimitiveBoolean", "typescript-core/src/Primitives/PrimitiveInteger", "typescript-core/src/Services/ServiceActionReturn", "typescript-core/src/Forms/Form", "typescript-core/src/Services/ServiceActionParams", "typescript-core/src/Services/ServiceAction"], function (require, exports, BaseService_1, PrimitiveBoolean_1, PrimitiveInteger_1, ServiceActionReturn_1, Form_1, ServiceActionParams_1, ServiceAction_1) {
    "use strict";
    var TestService = (function (_super) {
        __extends(TestService, _super);
        function TestService() {
            _super.apply(this, arguments);
            this.actions = {
                'getList': function () { return new ServiceAction_1.ServiceAction('GET', '/list/:userId', new ServiceActionParams_1.ServiceActionParams(new Form_1.Form({
                    'page': new PrimitiveInteger_1.PrimitiveInteger()
                }), null, null), new ServiceActionReturn_1.ServiceActionReturn(new PrimitiveBoolean_1.PrimitiveBoolean())); }
            };
        }
        TestService.prototype.getList = function (page) {
            if (page === void 0) { page = 0; }
            return this.action('getList').call(this.connector, {
                page: page
            });
        };
        return TestService;
    }(BaseService_1.BaseService));
    exports.TestService = TestService;
});
