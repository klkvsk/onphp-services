var __extends = (this && this.__extends) || function (d, b) {
    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
    function __() { this.constructor = d; }
    d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
};
System.register("typescript-core/src/Common/Dictionary", [], function(exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    return {
        setters:[],
        execute: function() {
        }
    }
});
System.register("typescript-core/src/Primitives/PrimitiveImportError", [], function(exports_2, context_2) {
    "use strict";
    var __moduleName = context_2 && context_2.id;
    var PrimitiveImportError;
    return {
        setters:[],
        execute: function() {
            (function (PrimitiveImportError) {
                PrimitiveImportError[PrimitiveImportError["WRONG"] = 1] = "WRONG";
                PrimitiveImportError[PrimitiveImportError["MISSING"] = 2] = "MISSING";
            })(PrimitiveImportError || (PrimitiveImportError = {}));
            exports_2("PrimitiveImportError", PrimitiveImportError);
        }
    }
});
System.register("typescript-core/src/Primitives/PrimitiveArray", ["typescript-core/src/Primitives/Primitive", "typescript-core/src/Primitives/PrimitiveImportError"], function(exports_3, context_3) {
    "use strict";
    var __moduleName = context_3 && context_3.id;
    var Primitive_1, PrimitiveImportError_1;
    var PrimitiveArray;
    return {
        setters:[
            function (Primitive_1_1) {
                Primitive_1 = Primitive_1_1;
            },
            function (PrimitiveImportError_1_1) {
                PrimitiveImportError_1 = PrimitiveImportError_1_1;
            }],
        execute: function() {
            PrimitiveArray = (function (_super) {
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
            exports_3("PrimitiveArray", PrimitiveArray);
        }
    }
});
System.register("typescript-core/src/Primitives/Primitive", ["typescript-core/src/Primitives/PrimitiveImportError"], function(exports_4, context_4) {
    "use strict";
    var __moduleName = context_4 && context_4.id;
    var PrimitiveImportError_2;
    var Primitive;
    return {
        setters:[
            function (PrimitiveImportError_2_1) {
                PrimitiveImportError_2 = PrimitiveImportError_2_1;
            }],
        execute: function() {
            Primitive = (function () {
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
                return Primitive;
            }());
            exports_4("Primitive", Primitive);
        }
    }
});
System.register("typescript-core/src/Primitives/PrimitiveString", ["typescript-core/src/Primitives/Primitive"], function(exports_5, context_5) {
    "use strict";
    var __moduleName = context_5 && context_5.id;
    var Primitive_2;
    var PrimitiveString;
    return {
        setters:[
            function (Primitive_2_1) {
                Primitive_2 = Primitive_2_1;
            }],
        execute: function() {
            PrimitiveString = (function (_super) {
                __extends(PrimitiveString, _super);
                function PrimitiveString() {
                    _super.apply(this, arguments);
                }
                PrimitiveString.prototype.importValue = function (data) {
                    this.value = "" + data;
                    return this.checkImportResult();
                };
                return PrimitiveString;
            }(Primitive_2.Primitive));
            exports_5("PrimitiveString", PrimitiveString);
        }
    }
});
System.register("typescript-core/src/Common/Exceptions", [], function(exports_6, context_6) {
    "use strict";
    var __moduleName = context_6 && context_6.id;
    var Exception, BusinessLogicException, NetworkException, ObjectNotFoundException, WrongArgumentException, UnimplementedFeatureException;
    return {
        setters:[],
        execute: function() {
            Exception = (function (_super) {
                __extends(Exception, _super);
                function Exception(message) {
                    _super.call(this, message);
                    this.message = message;
                }
                return Exception;
            }(Error));
            exports_6("Exception", Exception);
            BusinessLogicException = (function (_super) {
                __extends(BusinessLogicException, _super);
                function BusinessLogicException() {
                    _super.apply(this, arguments);
                }
                return BusinessLogicException;
            }(Exception));
            exports_6("BusinessLogicException", BusinessLogicException);
            NetworkException = (function (_super) {
                __extends(NetworkException, _super);
                function NetworkException() {
                    _super.apply(this, arguments);
                }
                return NetworkException;
            }(Exception));
            exports_6("NetworkException", NetworkException);
            ObjectNotFoundException = (function (_super) {
                __extends(ObjectNotFoundException, _super);
                function ObjectNotFoundException() {
                    _super.apply(this, arguments);
                }
                return ObjectNotFoundException;
            }(Exception));
            exports_6("ObjectNotFoundException", ObjectNotFoundException);
            WrongArgumentException = (function (_super) {
                __extends(WrongArgumentException, _super);
                function WrongArgumentException() {
                    _super.apply(this, arguments);
                }
                return WrongArgumentException;
            }(Exception));
            exports_6("WrongArgumentException", WrongArgumentException);
            UnimplementedFeatureException = (function (_super) {
                __extends(UnimplementedFeatureException, _super);
                function UnimplementedFeatureException() {
                    _super.apply(this, arguments);
                }
                return UnimplementedFeatureException;
            }(Exception));
            exports_6("UnimplementedFeatureException", UnimplementedFeatureException);
        }
    }
});
System.register("typescript-core/src/Forms/Form", ["typescript-core/src/Primitives/PrimitiveString", "typescript-core/src/Common/Exceptions"], function(exports_7, context_7) {
    "use strict";
    var __moduleName = context_7 && context_7.id;
    var PrimitiveString_1, Exceptions_1;
    var PrimitiveFactory, Form;
    return {
        setters:[
            function (PrimitiveString_1_1) {
                PrimitiveString_1 = PrimitiveString_1_1;
            },
            function (Exceptions_1_1) {
                Exceptions_1 = Exceptions_1_1;
            }],
        execute: function() {
            PrimitiveFactory = (function () {
                function PrimitiveFactory() {
                }
                PrimitiveFactory.prototype.test = function () {
                    return new PrimitiveString_1.PrimitiveString();
                };
                return PrimitiveFactory;
            }());
            exports_7("PrimitiveFactory", PrimitiveFactory);
            Form = (function () {
                function Form(primitives) {
                    this.primitives = {};
                    if (primitives) {
                        for (var name_1 in primitives) {
                            this.add(name_1, primitives[name_1]);
                        }
                    }
                }
                Form.prototype.getPrimitives = function () {
                    return this.primitives;
                };
                Form.prototype.getPrimitiveNames = function () {
                    var names = [];
                    for (var name_2 in this.primitives) {
                        names.push(name_2);
                    }
                    return names;
                };
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
                    for (var name_3 in this.primitives) {
                        if (data.hasOwnProperty(name_3)) {
                            result = result && this.primitives[name_3].importValue(data[name_3]);
                        }
                    }
                    return result;
                };
                Form.prototype.getErrors = function () {
                    var errors = {};
                    for (var name_4 in this.primitives) {
                        if (this.primitives[name_4].getError()) {
                            errors[name_4] = this.primitives[name_4].getError();
                        }
                    }
                    return errors;
                };
                Form.prototype.getValue = function (name) {
                    if (this.primitives.hasOwnProperty(name)) {
                        return this.primitives[name].getValue();
                    }
                    else {
                        throw new Exceptions_1.ObjectNotFoundException('form has no primitive "' + name + '"');
                    }
                };
                Form.prototype.exportData = function () {
                    var data = {};
                    for (var name_5 in this.primitives) {
                        data[name_5] = this.primitives[name_5].getValue();
                    }
                    return data;
                };
                return Form;
            }());
            exports_7("Form", Form);
        }
    }
});
System.register("typescript-core/src/Structures/StructureProto", ["typescript-core/src/Forms/Form"], function(exports_8, context_8) {
    "use strict";
    var __moduleName = context_8 && context_8.id;
    var Form_1;
    var StructureProto;
    return {
        setters:[
            function (Form_1_1) {
                Form_1 = Form_1_1;
            }],
        execute: function() {
            StructureProto = (function () {
                function StructureProto() {
                }
                StructureProto.prototype.getForm = function () {
                    return new Form_1.Form();
                };
                StructureProto.forClass = function (constructor) {
                    return constructor.proto();
                };
                StructureProto.prototype.make = function (data) {
                    var constructor = this.getConstructor();
                    var structure = new constructor();
                    this.fill(structure, data);
                    return structure;
                };
                StructureProto.prototype.fill = function (object, data) {
                    var form = this.getForm();
                    form.importData(data);
                    for (var name_6 in form.exportData()) {
                        object[name_6] = form.getValue(name_6);
                    }
                    return this;
                };
                return StructureProto;
            }());
            exports_8("StructureProto", StructureProto);
        }
    }
});
System.register("typescript-core/src/Structures/BaseStructure", ["typescript-core/src/Common/Exceptions"], function(exports_9, context_9) {
    "use strict";
    var __moduleName = context_9 && context_9.id;
    var Exceptions_2;
    var BaseStructure;
    return {
        setters:[
            function (Exceptions_2_1) {
                Exceptions_2 = Exceptions_2_1;
            }],
        execute: function() {
            BaseStructure = (function () {
                function BaseStructure() {
                }
                BaseStructure.proto = function () {
                    throw new Exceptions_2.UnimplementedFeatureException();
                };
                return BaseStructure;
            }());
            exports_9("BaseStructure", BaseStructure);
        }
    }
});
System.register("typescript-core/src/Common/Singleton", [], function(exports_10, context_10) {
    "use strict";
    var __moduleName = context_10 && context_10.id;
    var Singleton;
    return {
        setters:[],
        execute: function() {
            Singleton = (function () {
                function Singleton() {
                }
                Singleton.getInstance = function (classRef) {
                    var key = classRef.name;
                    if (!Singleton.instances.hasOwnProperty(key)) {
                        Singleton.instances[key] = (new classRef());
                    }
                    return Singleton.instances[key];
                };
                Singleton.instances = {};
                return Singleton;
            }());
            exports_10("Singleton", Singleton);
        }
    }
});
System.register("example/client/src/out/Auto/Structures/Proto/AutoProtoCat", ["example/client/src/out/Structures/Cat", "typescript-core/src/Primitives/PrimitiveString", "typescript-core/src/Structures/StructureProto", "typescript-core/src/Forms/Form"], function(exports_11, context_11) {
    "use strict";
    var __moduleName = context_11 && context_11.id;
    var Cat_1, PrimitiveString_2, StructureProto_1, Form_2;
    var AutoProtoCat;
    return {
        setters:[
            function (Cat_1_1) {
                Cat_1 = Cat_1_1;
            },
            function (PrimitiveString_2_1) {
                PrimitiveString_2 = PrimitiveString_2_1;
            },
            function (StructureProto_1_1) {
                StructureProto_1 = StructureProto_1_1;
            },
            function (Form_2_1) {
                Form_2 = Form_2_1;
            }],
        execute: function() {
            /**
             * Created by klkvsk on 08.10.2016.
             */
            AutoProtoCat = (function (_super) {
                __extends(AutoProtoCat, _super);
                function AutoProtoCat() {
                    _super.apply(this, arguments);
                }
                AutoProtoCat.prototype.getConstructor = function () {
                    return Cat_1.Cat;
                };
                AutoProtoCat.prototype.getForm = function () {
                    return new Form_2.Form()
                        .add('name', new PrimitiveString_2.PrimitiveString()
                        .setRequired(true));
                };
                return AutoProtoCat;
            }(StructureProto_1.StructureProto));
            exports_11("AutoProtoCat", AutoProtoCat);
        }
    }
});
System.register("example/client/src/out/Structures/Proto/ProtoCat", ["example/client/src/out/Auto/Structures/Proto/AutoProtoCat"], function(exports_12, context_12) {
    "use strict";
    var __moduleName = context_12 && context_12.id;
    var AutoProtoCat_1;
    var ProtoCat;
    return {
        setters:[
            function (AutoProtoCat_1_1) {
                AutoProtoCat_1 = AutoProtoCat_1_1;
            }],
        execute: function() {
            /**
             * Created by klkvsk on 08.10.2016.
             */
            ProtoCat = (function (_super) {
                __extends(ProtoCat, _super);
                function ProtoCat() {
                    _super.apply(this, arguments);
                }
                return ProtoCat;
            }(AutoProtoCat_1.AutoProtoCat));
            exports_12("ProtoCat", ProtoCat);
        }
    }
});
System.register("example/client/src/out/Auto/Structures/AutoCat", ["typescript-core/src/Structures/BaseStructure", "typescript-core/src/Common/Singleton", "example/client/src/out/Structures/Proto/ProtoCat"], function(exports_13, context_13) {
    "use strict";
    var __moduleName = context_13 && context_13.id;
    var BaseStructure_1, Singleton_1, ProtoCat_1;
    var AutoCat;
    return {
        setters:[
            function (BaseStructure_1_1) {
                BaseStructure_1 = BaseStructure_1_1;
            },
            function (Singleton_1_1) {
                Singleton_1 = Singleton_1_1;
            },
            function (ProtoCat_1_1) {
                ProtoCat_1 = ProtoCat_1_1;
            }],
        execute: function() {
            AutoCat = (function (_super) {
                __extends(AutoCat, _super);
                function AutoCat() {
                    _super.apply(this, arguments);
                    this.name = null;
                }
                AutoCat.proto = function () {
                    return Singleton_1.Singleton.getInstance(ProtoCat_1.ProtoCat);
                };
                AutoCat.prototype.setName = function (name) {
                    if (name === void 0) { name = null; }
                    this.name = name;
                    return this;
                };
                AutoCat.prototype.getName = function () {
                    return this.name;
                };
                return AutoCat;
            }(BaseStructure_1.BaseStructure));
            exports_13("AutoCat", AutoCat);
        }
    }
});
System.register("example/client/src/out/Structures/Cat", ["example/client/src/out/Auto/Structures/AutoCat"], function(exports_14, context_14) {
    "use strict";
    var __moduleName = context_14 && context_14.id;
    var AutoCat_1;
    var Cat;
    return {
        setters:[
            function (AutoCat_1_1) {
                AutoCat_1 = AutoCat_1_1;
            }],
        execute: function() {
            Cat = (function (_super) {
                __extends(Cat, _super);
                function Cat() {
                    _super.apply(this, arguments);
                }
                return Cat;
            }(AutoCat_1.AutoCat));
            exports_14("Cat", Cat);
        }
    }
});
System.register("typescript-core/src/Services/IServiceConnector", [], function(exports_15, context_15) {
    "use strict";
    var __moduleName = context_15 && context_15.id;
    return {
        setters:[],
        execute: function() {
        }
    }
});
System.register("typescript-core/src/Services/ServiceActionParams", [], function(exports_16, context_16) {
    "use strict";
    var __moduleName = context_16 && context_16.id;
    var ServiceActionParams;
    return {
        setters:[],
        execute: function() {
            ServiceActionParams = (function () {
                function ServiceActionParams(route, query, body) {
                    this.route = route;
                    this.query = query;
                    this.body = body;
                }
                return ServiceActionParams;
            }());
            exports_16("ServiceActionParams", ServiceActionParams);
        }
    }
});
System.register("typescript-core/src/Services/ServiceActionReturn", [], function(exports_17, context_17) {
    "use strict";
    var __moduleName = context_17 && context_17.id;
    var ServiceActionReturn;
    return {
        setters:[],
        execute: function() {
            ServiceActionReturn = (function () {
                function ServiceActionReturn(primitive) {
                    this.primitive = primitive;
                }
                return ServiceActionReturn;
            }());
            exports_17("ServiceActionReturn", ServiceActionReturn);
        }
    }
});
System.register("typescript-core/src/Forms/FormValidationException", ["typescript-core/src/Common/Exceptions"], function(exports_18, context_18) {
    "use strict";
    var __moduleName = context_18 && context_18.id;
    var Exceptions_3;
    var FormValidationException;
    return {
        setters:[
            function (Exceptions_3_1) {
                Exceptions_3 = Exceptions_3_1;
            }],
        execute: function() {
            FormValidationException = (function (_super) {
                __extends(FormValidationException, _super);
                function FormValidationException(errors) {
                    _super.call(this);
                    this.errors = errors;
                }
                FormValidationException.prototype.toString = function () {
                    return JSON.stringify(this.errors);
                };
                return FormValidationException;
            }(Exceptions_3.Exception));
            exports_18("FormValidationException", FormValidationException);
        }
    }
});
System.register("typescript-core/src/Services/ServiceAction", ["typescript-core/src/Forms/FormValidationException"], function(exports_19, context_19) {
    "use strict";
    var __moduleName = context_19 && context_19.id;
    var FormValidationException_1;
    var ServiceAction;
    return {
        setters:[
            function (FormValidationException_1_1) {
                FormValidationException_1 = FormValidationException_1_1;
            }],
        execute: function() {
            ServiceAction = (function () {
                function ServiceAction(httpMethod, httpPath, params, returns) {
                    this.httpMethod = httpMethod;
                    this.httpPath = httpPath;
                    this.params = params;
                    this.returns = returns;
                }
                ServiceAction.prototype.call = function (connector, params) {
                    var _this = this;
                    var errors = {};
                    for (var _i = 0, _a = [this.params.route, this.params.query, this.params.body]; _i < _a.length; _i++) {
                        var p = _a[_i];
                        if (!p) {
                            continue;
                        }
                        if (!p.importData(params)) {
                            var e = p.getErrors();
                            for (var name_7 in e) {
                                errors[name_7] = e[name_7];
                            }
                        }
                    }
                    if (Object.keys(errors).length > 0) {
                        throw new FormValidationException_1.FormValidationException(errors);
                    }
                    // put route parameters into :placeholders
                    var httpPath = this.httpPath.replace(/:([a-z][a-z0-9_]+)/ig, function (_, placeholderName) {
                        if (!_this.params.route) {
                            return '';
                        }
                        var value = _this.params.route.getValue(placeholderName);
                        return value ? value.toString() : '';
                    });
                    var request = {
                        httpMethod: this.httpMethod,
                        httpPath: httpPath,
                        query: this.params.query ? this.params.query.exportData() : null,
                        body: this.params.body ? this.params.body.exportData() : null,
                    };
                    return connector.doRequest(request);
                };
                return ServiceAction;
            }());
            exports_19("ServiceAction", ServiceAction);
        }
    }
});
System.register("typescript-core/src/Primitives/PrimitiveBoolean", ["typescript-core/src/Primitives/Primitive"], function(exports_20, context_20) {
    "use strict";
    var __moduleName = context_20 && context_20.id;
    var Primitive_3;
    var PrimitiveBoolean;
    return {
        setters:[
            function (Primitive_3_1) {
                Primitive_3 = Primitive_3_1;
            }],
        execute: function() {
            PrimitiveBoolean = (function (_super) {
                __extends(PrimitiveBoolean, _super);
                function PrimitiveBoolean() {
                    _super.apply(this, arguments);
                }
                PrimitiveBoolean.prototype.importValue = function (data) {
                    this.value = !!data;
                    return this.checkImportResult();
                };
                return PrimitiveBoolean;
            }(Primitive_3.Primitive));
            exports_20("PrimitiveBoolean", PrimitiveBoolean);
        }
    }
});
System.register("typescript-core/src/Primitives/PrimitiveInteger", ["typescript-core/src/Primitives/Primitive"], function(exports_21, context_21) {
    "use strict";
    var __moduleName = context_21 && context_21.id;
    var Primitive_4;
    var PrimitiveInteger;
    return {
        setters:[
            function (Primitive_4_1) {
                Primitive_4 = Primitive_4_1;
            }],
        execute: function() {
            PrimitiveInteger = (function (_super) {
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
            }(Primitive_4.Primitive));
            exports_21("PrimitiveInteger", PrimitiveInteger);
        }
    }
});
System.register("typescript-core/src/Services/BaseService", [], function(exports_22, context_22) {
    "use strict";
    var __moduleName = context_22 && context_22.id;
    var BaseService;
    return {
        setters:[],
        execute: function() {
            BaseService = (function () {
                function BaseService(connector) {
                    this.connector = connector;
                    this.actionInstances = {};
                }
                BaseService.prototype.action = function (name) {
                    return this.actionInstances[name] = this.actionInstances[name] || this.actions[name]();
                };
                ;
                return BaseService;
            }());
            exports_22("BaseService", BaseService);
        }
    }
});
System.register("example/client/src/out/Services/TestService", ["typescript-core/src/Services/BaseService", "typescript-core/src/Primitives/PrimitiveInteger", "typescript-core/src/Forms/Form", "typescript-core/src/Services/ServiceActionParams", "typescript-core/src/Primitives/PrimitiveBoolean", "typescript-core/src/Services/ServiceActionReturn", "typescript-core/src/Services/ServiceAction"], function(exports_23, context_23) {
    "use strict";
    var __moduleName = context_23 && context_23.id;
    var BaseService_1, PrimitiveInteger_1, Form_3, ServiceActionParams_1, PrimitiveBoolean_1, ServiceActionReturn_1, ServiceAction_1;
    var TestService;
    return {
        setters:[
            function (BaseService_1_1) {
                BaseService_1 = BaseService_1_1;
            },
            function (PrimitiveInteger_1_1) {
                PrimitiveInteger_1 = PrimitiveInteger_1_1;
            },
            function (Form_3_1) {
                Form_3 = Form_3_1;
            },
            function (ServiceActionParams_1_1) {
                ServiceActionParams_1 = ServiceActionParams_1_1;
            },
            function (PrimitiveBoolean_1_1) {
                PrimitiveBoolean_1 = PrimitiveBoolean_1_1;
            },
            function (ServiceActionReturn_1_1) {
                ServiceActionReturn_1 = ServiceActionReturn_1_1;
            },
            function (ServiceAction_1_1) {
                ServiceAction_1 = ServiceAction_1_1;
            }],
        execute: function() {
            TestService = (function (_super) {
                __extends(TestService, _super);
                function TestService() {
                    _super.apply(this, arguments);
                    this.actions = {
                        'getList': function () { return new ServiceAction_1.ServiceAction('GET', '/list/:page', new ServiceActionParams_1.ServiceActionParams(new Form_3.Form({
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
            exports_23("TestService", TestService);
        }
    }
});
System.register("typescript-core/src/Services/AxiosServiceAdapter", ['axios'], function(exports_24, context_24) {
    "use strict";
    var __moduleName = context_24 && context_24.id;
    var axios_1;
    var AxiosServiceConnector;
    return {
        setters:[
            function (axios_1_1) {
                axios_1 = axios_1_1;
            }],
        execute: function() {
            AxiosServiceConnector = (function () {
                function AxiosServiceConnector() {
                }
                AxiosServiceConnector.configure = function (config) {
                    this.config = config;
                    this.axios = null;
                };
                AxiosServiceConnector.prototype.doRequest = function (request) {
                    if (!AxiosServiceConnector.axios) {
                        AxiosServiceConnector.axios = axios_1.default.create(AxiosServiceConnector.config);
                    }
                    var axRequest = {
                        url: request.httpPath,
                        headers: request.headers,
                        method: request.httpMethod,
                        params: request.query,
                        data: request.body,
                    };
                    return AxiosServiceConnector.axios.request(axRequest)
                        .then(function (axResponse) { return {
                        statusCode: axResponse.status,
                        statusText: axResponse.statusText,
                        headers: axResponse.headers,
                        body: axResponse.data
                    }; });
                };
                return AxiosServiceConnector;
            }());
            exports_24("AxiosServiceConnector", AxiosServiceConnector);
        }
    }
});
System.register("example/client/src/app", ["example/client/src/out/Structures/Cat", "example/client/src/out/Services/TestService", "typescript-core/src/Services/AxiosServiceAdapter"], function(exports_25, context_25) {
    "use strict";
    var __moduleName = context_25 && context_25.id;
    var Cat_2, TestService_1, AxiosServiceAdapter_1;
    var cat, t;
    return {
        setters:[
            function (Cat_2_1) {
                Cat_2 = Cat_2_1;
            },
            function (TestService_1_1) {
                TestService_1 = TestService_1_1;
            },
            function (AxiosServiceAdapter_1_1) {
                AxiosServiceAdapter_1 = AxiosServiceAdapter_1_1;
            }],
        execute: function() {
            cat = Cat_2.Cat.proto().make({ name: 'purrr' });
            t = new TestService_1.TestService(new AxiosServiceAdapter_1.AxiosServiceConnector());
            t.getList().then(function (list) {
                console.log(list);
            });
        }
    }
});
