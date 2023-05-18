export default class Role {

    constructor(permission) {
        this.permission = permission;
    }

    isPermission(data) {
        if (this.permission.some(value => value === data)) {
            return true;
        } else {
            return false;
        }
    }
    isAction(data1, data2) {
        if (this.permission.some(value => value === data1) || this.permission.some(value => value === data2)) {
            return true;
        } else {
            return false;
        }
    }
    isMapping(data1, data2, data3) {
        if (this.permission.some(value => value === data1) || this.permission.some(value => value === data2) || this.permission.some(value => value === data3)) {
            return true;
        } else {
            return false;
        }
    }
}