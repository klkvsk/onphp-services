"use strict";
var __extends = (this && this.__extends) || function (d, b) {
    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
    function __() { this.constructor = d; }
    d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
};
var BaseService_1 = require("typescript-core/src/Services/BaseService");
var PrimitiveBoolean_1 = require("../../../typescript-core/src/Primitives/PrimitiveBoolean");
var PrimitiveInteger_1 = require("../../../typescript-core/src/Primitives/PrimitiveInteger");
var ServiceActionReturn_1 = require("../../../typescript-core/src/Services/ServiceActionReturn");
var Form_1 = require("../../../typescript-core/src/Forms/Form");
var ServiceActionParams_1 = require("../../../typescript-core/src/Services/ServiceActionParams");
var ServiceAction_1 = require("../../../typescript-core/src/Services/ServiceAction");
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
