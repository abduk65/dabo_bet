import { ref } from 'vue'
export  const ssalesData = ref([
  {
    "id": 1,
    "product_id": 1,
    "quantity": 812,
    "adari": 26,
    "branch_id": 2,
    "commission_id": 14,
    "commission_quantity": 32,
    "created_at": "2024-06-28T00:24:48.000000Z",
    "updated_at": "2024-06-28T00:24:48.000000Z",
    "product": {
      "id": 1,
      "name": "bale7",
      "type": "Bread",
      "unit_price": 7,
      "created_at": "2024-07-16T14:32:41.000000Z",
      "updated_at": "2024-07-16T14:32:41.000000Z"
    },
    "branch": {
      "id": 2,
      "name": "tach_bet",
      "type": "sub",
      "created_at": "2024-07-19T08:58:42.000000Z",
      "updated_at": "2024-07-19T08:58:42.000000Z"
    },
    "commission": {
      "id": 14,
      "product_id": 4,
      "discount_amount": 0.66,
      "commission_recipient_id": 3,
      "status": "inactive",
      "created_at": "2024-07-19T10:05:24.000000Z",
      "updated_at": "2024-07-19T10:05:24.000000Z"
    }
  },
  {
    "id": 2,
    "product_id": 3,
    "quantity": 1648,
    "adari": 33,
    "branch_id": 2,
    "commission_id": 3,
    "commission_quantity": 15,
    "created_at": "2024-05-30T12:18:38.000000Z",
    "updated_at": "2024-05-30T12:18:38.000000Z",
    "product": {
      "id": 3,
      "name": "Donut",
      "type": "Cake",
      "unit_price": 30,
      "created_at": "2024-07-16T14:32:41.000000Z",
      "updated_at": "2024-07-16T14:32:41.000000Z"
    },
    "branch": {
      "id": 2,
      "name": "tach_bet",
      "type": "sub",
      "created_at": "2024-07-19T08:58:42.000000Z",
      "updated_at": "2024-07-19T08:58:42.000000Z"
    },
    "commission": {
      "id": 3,
      "product_id": 2,
      "discount_amount": 0.85,
      "commission_recipient_id": 5,
      "status": "active",
      "created_at": "2024-07-19T10:05:24.000000Z",
      "updated_at": "2024-07-19T10:05:24.000000Z"
    }
  },
  {
    "id": 3,
    "product_id": 2,
    "quantity": 1736,
    "adari": 25,
    "branch_id": 1,
    "commission_id": 15,
    "commission_quantity": 44,
    "created_at": "2024-07-04T03:08:07.000000Z",
    "updated_at": "2024-07-04T03:08:07.000000Z",
    "product": {
      "id": 2,
      "name": "bale10",
      "type": "Bread",
      "unit_price": 10,
      "created_at": "2024-07-16T14:32:41.000000Z",
      "updated_at": "2024-07-16T14:32:41.000000Z"
    },
    "branch": {
      "id": 1,
      "name": "saris",
      "type": "main",
      "created_at": "2024-07-19T08:58:42.000000Z",
      "updated_at": "2024-07-19T08:58:42.000000Z"
    },
    "commission": {
      "id": 15,
      "product_id": 2,
      "discount_amount": 0.92,
      "commission_recipient_id": 4,
      "status": "inactive",
      "created_at": "2024-07-19T10:05:24.000000Z",
      "updated_at": "2024-07-19T10:05:24.000000Z"
    }
  },
  {
    "id": 4,
    "product_id": 2,
    "quantity": 4326,
    "adari": 36,
    "branch_id": 2,
    "commission_id": 17,
    "commission_quantity": 16,
    "created_at": "2024-07-12T21:30:41.000000Z",
    "updated_at": "2024-07-12T21:30:41.000000Z",
    "product": {
      "id": 2,
      "name": "bale10",
      "type": "Bread",
      "unit_price": 10,
      "created_at": "2024-07-16T14:32:41.000000Z",
      "updated_at": "2024-07-16T14:32:41.000000Z"
    },
    "branch": {
      "id": 2,
      "name": "tach_bet",
      "type": "sub",
      "created_at": "2024-07-19T08:58:42.000000Z",
      "updated_at": "2024-07-19T08:58:42.000000Z"
    },
    "commission": {
      "id": 17,
      "product_id": 3,
      "discount_amount": 0.81,
      "commission_recipient_id": 2,
      "status": "inactive",
      "created_at": "2024-07-19T10:05:24.000000Z",
      "updated_at": "2024-07-19T10:05:24.000000Z"
    }
  },
  {
    "id": 5,
    "product_id": 4,
    "quantity": 4516,
    "adari": 27,
    "branch_id": 1,
    "commission_id": 12,
    "commission_quantity": 10,
    "created_at": "2024-01-29T00:59:05.000000Z",
    "updated_at": "2024-01-29T00:59:05.000000Z",
    "product": {
      "id": 4,
      "name": "Kuribat",
      "type": "Cake",
      "unit_price": 40,
      "created_at": "2024-07-16T14:32:41.000000Z",
      "updated_at": "2024-07-16T14:32:41.000000Z"
    },
    "branch": {
      "id": 1,
      "name": "saris",
      "type": "main",
      "created_at": "2024-07-19T08:58:42.000000Z",
      "updated_at": "2024-07-19T08:58:42.000000Z"
    },
    "commission": {
      "id": 12,
      "product_id": 4,
      "discount_amount": 0.39,
      "commission_recipient_id": 3,
      "status": "active",
      "created_at": "2024-07-19T10:05:24.000000Z",
      "updated_at": "2024-07-19T10:05:24.000000Z"
    }
  },
  {
    "id": 6,
    "product_id": 2,
    "quantity": 4061,
    "adari": 17,
    "branch_id": 1,
    "commission_id": 9,
    "commission_quantity": 17,
    "created_at": "2024-03-20T13:33:42.000000Z",
    "updated_at": "2024-03-20T13:33:42.000000Z",
    "product": {
      "id": 2,
      "name": "bale10",
      "type": "Bread",
      "unit_price": 10,
      "created_at": "2024-07-16T14:32:41.000000Z",
      "updated_at": "2024-07-16T14:32:41.000000Z"
    },
    "branch": {
      "id": 1,
      "name": "saris",
      "type": "main",
      "created_at": "2024-07-19T08:58:42.000000Z",
      "updated_at": "2024-07-19T08:58:42.000000Z"
    },
    "commission": {
      "id": 9,
      "product_id": 2,
      "discount_amount": 0.48,
      "commission_recipient_id": 3,
      "status": "active",
      "created_at": "2024-07-19T10:05:24.000000Z",
      "updated_at": "2024-07-19T10:05:24.000000Z"
    }
  },
  {
    "id": 7,
    "product_id": 4,
    "quantity": 1732,
    "adari": 36,
    "branch_id": 2,
    "commission_id": 19,
    "commission_quantity": 43,
    "created_at": "2024-02-02T02:58:59.000000Z",
    "updated_at": "2024-02-02T02:58:59.000000Z",
    "product": {
      "id": 4,
      "name": "Kuribat",
      "type": "Cake",
      "unit_price": 40,
      "created_at": "2024-07-16T14:32:41.000000Z",
      "updated_at": "2024-07-16T14:32:41.000000Z"
    },
    "branch": {
      "id": 2,
      "name": "tach_bet",
      "type": "sub",
      "created_at": "2024-07-19T08:58:42.000000Z",
      "updated_at": "2024-07-19T08:58:42.000000Z"
    },
    "commission": {
      "id": 19,
      "product_id": 1,
      "discount_amount": 0.9,
      "commission_recipient_id": 3,
      "status": "inactive",
      "created_at": "2024-07-19T10:05:24.000000Z",
      "updated_at": "2024-07-19T10:05:24.000000Z"
    }
  },
  {
    "id": 8,
    "product_id": 2,
    "quantity": 4124,
    "adari": 4,
    "branch_id": 2,
    "commission_id": 17,
    "commission_quantity": 47,
    "created_at": "2024-05-13T09:11:39.000000Z",
    "updated_at": "2024-05-13T09:11:39.000000Z",
    "product": {
      "id": 2,
      "name": "bale10",
      "type": "Bread",
      "unit_price": 10,
      "created_at": "2024-07-16T14:32:41.000000Z",
      "updated_at": "2024-07-16T14:32:41.000000Z"
    },
    "branch": {
      "id": 2,
      "name": "tach_bet",
      "type": "sub",
      "created_at": "2024-07-19T08:58:42.000000Z",
      "updated_at": "2024-07-19T08:58:42.000000Z"
    },
    "commission": {
      "id": 17,
      "product_id": 3,
      "discount_amount": 0.81,
      "commission_recipient_id": 2,
      "status": "inactive",
      "created_at": "2024-07-19T10:05:24.000000Z",
      "updated_at": "2024-07-19T10:05:24.000000Z"
    }
  },
  {
    "id": 9,
    "product_id": 2,
    "quantity": 1090,
    "adari": 25,
    "branch_id": 2,
    "commission_id": 14,
    "commission_quantity": 13,
    "created_at": "2024-05-03T02:27:13.000000Z",
    "updated_at": "2024-05-03T02:27:13.000000Z",
    "product": {
      "id": 2,
      "name": "bale10",
      "type": "Bread",
      "unit_price": 10,
      "created_at": "2024-07-16T14:32:41.000000Z",
      "updated_at": "2024-07-16T14:32:41.000000Z"
    },
    "branch": {
      "id": 2,
      "name": "tach_bet",
      "type": "sub",
      "created_at": "2024-07-19T08:58:42.000000Z",
      "updated_at": "2024-07-19T08:58:42.000000Z"
    },
    "commission": {
      "id": 14,
      "product_id": 4,
      "discount_amount": 0.66,
      "commission_recipient_id": 3,
      "status": "inactive",
      "created_at": "2024-07-19T10:05:24.000000Z",
      "updated_at": "2024-07-19T10:05:24.000000Z"
    }
  },
  {
    "id": 10,
    "product_id": 3,
    "quantity": 3426,
    "adari": 24,
    "branch_id": 1,
    "commission_id": 20,
    "commission_quantity": 3,
    "created_at": "2024-02-22T04:38:56.000000Z",
    "updated_at": "2024-02-22T04:38:56.000000Z",
    "product": {
      "id": 3,
      "name": "Donut",
      "type": "Cake",
      "unit_price": 30,
      "created_at": "2024-07-16T14:32:41.000000Z",
      "updated_at": "2024-07-16T14:32:41.000000Z"
    },
    "branch": {
      "id": 1,
      "name": "saris",
      "type": "main",
      "created_at": "2024-07-19T08:58:42.000000Z",
      "updated_at": "2024-07-19T08:58:42.000000Z"
    },
    "commission": {
      "id": 20,
      "product_id": 2,
      "discount_amount": 0.37,
      "commission_recipient_id": 4,
      "status": "active",
      "created_at": "2024-07-19T10:05:24.000000Z",
      "updated_at": "2024-07-19T10:05:24.000000Z"
    }
  },
  {
    "id": 11,
    "product_id": 3,
    "quantity": 4031,
    "adari": 25,
    "branch_id": 2,
    "commission_id": 3,
    "commission_quantity": 33,
    "created_at": "2024-06-20T02:31:29.000000Z",
    "updated_at": "2024-06-20T02:31:29.000000Z",
    "product": {
      "id": 3,
      "name": "Donut",
      "type": "Cake",
      "unit_price": 30,
      "created_at": "2024-07-16T14:32:41.000000Z",
      "updated_at": "2024-07-16T14:32:41.000000Z"
    },
    "branch": {
      "id": 2,
      "name": "tach_bet",
      "type": "sub",
      "created_at": "2024-07-19T08:58:42.000000Z",
      "updated_at": "2024-07-19T08:58:42.000000Z"
    },
    "commission": {
      "id": 3,
      "product_id": 2,
      "discount_amount": 0.85,
      "commission_recipient_id": 5,
      "status": "active",
      "created_at": "2024-07-19T10:05:24.000000Z",
      "updated_at": "2024-07-19T10:05:24.000000Z"
    }
  },
  {
    "id": 12,
    "product_id": 3,
    "quantity": 862,
    "adari": 18,
    "branch_id": 2,
    "commission_id": 4,
    "commission_quantity": 21,
    "created_at": "2024-02-22T16:25:07.000000Z",
    "updated_at": "2024-02-22T16:25:07.000000Z",
    "product": {
      "id": 3,
      "name": "Donut",
      "type": "Cake",
      "unit_price": 30,
      "created_at": "2024-07-16T14:32:41.000000Z",
      "updated_at": "2024-07-16T14:32:41.000000Z"
    },
    "branch": {
      "id": 2,
      "name": "tach_bet",
      "type": "sub",
      "created_at": "2024-07-19T08:58:42.000000Z",
      "updated_at": "2024-07-19T08:58:42.000000Z"
    },
    "commission": {
      "id": 4,
      "product_id": 4,
      "discount_amount": 0.09,
      "commission_recipient_id": 1,
      "status": "active",
      "created_at": "2024-07-19T10:05:24.000000Z",
      "updated_at": "2024-07-19T10:05:24.000000Z"
    }
  },
  {
    "id": 13,
    "product_id": 3,
    "quantity": 4884,
    "adari": 29,
    "branch_id": 2,
    "commission_id": 8,
    "commission_quantity": 8,
    "created_at": "2024-02-16T19:43:07.000000Z",
    "updated_at": "2024-02-16T19:43:07.000000Z",
    "product": {
      "id": 3,
      "name": "Donut",
      "type": "Cake",
      "unit_price": 30,
      "created_at": "2024-07-16T14:32:41.000000Z",
      "updated_at": "2024-07-16T14:32:41.000000Z"
    },
    "branch": {
      "id": 2,
      "name": "tach_bet",
      "type": "sub",
      "created_at": "2024-07-19T08:58:42.000000Z",
      "updated_at": "2024-07-19T08:58:42.000000Z"
    },
    "commission": {
      "id": 8,
      "product_id": 2,
      "discount_amount": 0.41,
      "commission_recipient_id": 2,
      "status": "inactive",
      "created_at": "2024-07-19T10:05:24.000000Z",
      "updated_at": "2024-07-19T10:05:24.000000Z"
    }
  },
  {
    "id": 14,
    "product_id": 4,
    "quantity": 1974,
    "adari": 38,
    "branch_id": 1,
    "commission_id": 17,
    "commission_quantity": 10,
    "created_at": "2024-07-17T23:55:08.000000Z",
    "updated_at": "2024-07-17T23:55:08.000000Z",
    "product": {
      "id": 4,
      "name": "Kuribat",
      "type": "Cake",
      "unit_price": 40,
      "created_at": "2024-07-16T14:32:41.000000Z",
      "updated_at": "2024-07-16T14:32:41.000000Z"
    },
    "branch": {
      "id": 1,
      "name": "saris",
      "type": "main",
      "created_at": "2024-07-19T08:58:42.000000Z",
      "updated_at": "2024-07-19T08:58:42.000000Z"
    },
    "commission": {
      "id": 17,
      "product_id": 3,
      "discount_amount": 0.81,
      "commission_recipient_id": 2,
      "status": "inactive",
      "created_at": "2024-07-19T10:05:24.000000Z",
      "updated_at": "2024-07-19T10:05:24.000000Z"
    }
  },
  {
    "id": 15,
    "product_id": 3,
    "quantity": 4774,
    "adari": 26,
    "branch_id": 1,
    "commission_id": 20,
    "commission_quantity": 6,
    "created_at": "2024-01-22T18:43:24.000000Z",
    "updated_at": "2024-01-22T18:43:24.000000Z",
    "product": {
      "id": 3,
      "name": "Donut",
      "type": "Cake",
      "unit_price": 30,
      "created_at": "2024-07-16T14:32:41.000000Z",
      "updated_at": "2024-07-16T14:32:41.000000Z"
    },
    "branch": {
      "id": 1,
      "name": "saris",
      "type": "main",
      "created_at": "2024-07-19T08:58:42.000000Z",
      "updated_at": "2024-07-19T08:58:42.000000Z"
    },
    "commission": {
      "id": 20,
      "product_id": 2,
      "discount_amount": 0.37,
      "commission_recipient_id": 4,
      "status": "active",
      "created_at": "2024-07-19T10:05:24.000000Z",
      "updated_at": "2024-07-19T10:05:24.000000Z"
    }
  },
  {
    "id": 16,
    "product_id": 4,
    "quantity": 762,
    "adari": 38,
    "branch_id": 1,
    "commission_id": 15,
    "commission_quantity": 25,
    "created_at": "2024-04-20T04:56:44.000000Z",
    "updated_at": "2024-04-20T04:56:44.000000Z",
    "product": {
      "id": 4,
      "name": "Kuribat",
      "type": "Cake",
      "unit_price": 40,
      "created_at": "2024-07-16T14:32:41.000000Z",
      "updated_at": "2024-07-16T14:32:41.000000Z"
    },
    "branch": {
      "id": 1,
      "name": "saris",
      "type": "main",
      "created_at": "2024-07-19T08:58:42.000000Z",
      "updated_at": "2024-07-19T08:58:42.000000Z"
    },
    "commission": {
      "id": 15,
      "product_id": 2,
      "discount_amount": 0.92,
      "commission_recipient_id": 4,
      "status": "inactive",
      "created_at": "2024-07-19T10:05:24.000000Z",
      "updated_at": "2024-07-19T10:05:24.000000Z"
    }
  },
  {
    "id": 17,
    "product_id": 1,
    "quantity": 968,
    "adari": 26,
    "branch_id": 2,
    "commission_id": 5,
    "commission_quantity": 44,
    "created_at": "2024-04-07T04:05:26.000000Z",
    "updated_at": "2024-04-07T04:05:26.000000Z",
    "product": {
      "id": 1,
      "name": "bale7",
      "type": "Bread",
      "unit_price": 7,
      "created_at": "2024-07-16T14:32:41.000000Z",
      "updated_at": "2024-07-16T14:32:41.000000Z"
    },
    "branch": {
      "id": 2,
      "name": "tach_bet",
      "type": "sub",
      "created_at": "2024-07-19T08:58:42.000000Z",
      "updated_at": "2024-07-19T08:58:42.000000Z"
    },
    "commission": {
      "id": 5,
      "product_id": 2,
      "discount_amount": 0.41,
      "commission_recipient_id": 4,
      "status": "inactive",
      "created_at": "2024-07-19T10:05:24.000000Z",
      "updated_at": "2024-07-19T10:05:24.000000Z"
    }
  },
  {
    "id": 18,
    "product_id": 4,
    "quantity": 3339,
    "adari": 29,
    "branch_id": 1,
    "commission_id": 1,
    "commission_quantity": 48,
    "created_at": "2024-02-27T03:39:18.000000Z",
    "updated_at": "2024-02-27T03:39:18.000000Z",
    "product": {
      "id": 4,
      "name": "Kuribat",
      "type": "Cake",
      "unit_price": 40,
      "created_at": "2024-07-16T14:32:41.000000Z",
      "updated_at": "2024-07-16T14:32:41.000000Z"
    },
    "branch": {
      "id": 1,
      "name": "saris",
      "type": "main",
      "created_at": "2024-07-19T08:58:42.000000Z",
      "updated_at": "2024-07-19T08:58:42.000000Z"
    },
    "commission": {
      "id": 1,
      "product_id": 4,
      "discount_amount": 0.49,
      "commission_recipient_id": 4,
      "status": "inactive",
      "created_at": "2024-07-19T10:05:24.000000Z",
      "updated_at": "2024-07-19T10:05:24.000000Z"
    }
  },
  {
    "id": 19,
    "product_id": 4,
    "quantity": 2051,
    "adari": 33,
    "branch_id": 1,
    "commission_id": 19,
    "commission_quantity": 5,
    "created_at": "2024-06-15T16:05:10.000000Z",
    "updated_at": "2024-06-15T16:05:10.000000Z",
    "product": {
      "id": 4,
      "name": "Kuribat",
      "type": "Cake",
      "unit_price": 40,
      "created_at": "2024-07-16T14:32:41.000000Z",
      "updated_at": "2024-07-16T14:32:41.000000Z"
    },
    "branch": {
      "id": 1,
      "name": "saris",
      "type": "main",
      "created_at": "2024-07-19T08:58:42.000000Z",
      "updated_at": "2024-07-19T08:58:42.000000Z"
    },
    "commission": {
      "id": 19,
      "product_id": 1,
      "discount_amount": 0.9,
      "commission_recipient_id": 3,
      "status": "inactive",
      "created_at": "2024-07-19T10:05:24.000000Z",
      "updated_at": "2024-07-19T10:05:24.000000Z"
    }
  },
  {
    "id": 20,
    "product_id": 2,
    "quantity": 3652,
    "adari": 7,
    "branch_id": 2,
    "commission_id": 13,
    "commission_quantity": 37,
    "created_at": "2024-03-30T15:13:38.000000Z",
    "updated_at": "2024-03-30T15:13:38.000000Z",
    "product": {
      "id": 2,
      "name": "bale10",
      "type": "Bread",
      "unit_price": 10,
      "created_at": "2024-07-16T14:32:41.000000Z",
      "updated_at": "2024-07-16T14:32:41.000000Z"
    },
    "branch": {
      "id": 2,
      "name": "tach_bet",
      "type": "sub",
      "created_at": "2024-07-19T08:58:42.000000Z",
      "updated_at": "2024-07-19T08:58:42.000000Z"
    },
    "commission": {
      "id": 13,
      "product_id": 3,
      "discount_amount": 0.17,
      "commission_recipient_id": 1,
      "status": "inactive",
      "created_at": "2024-07-19T10:05:24.000000Z",
      "updated_at": "2024-07-19T10:05:24.000000Z"
    }
  }
])


export const profitReport = {
  "dailySales": [
    {
      "id": 1,
      "product_id": 1,
      "quantity": 812,
      "adari": 26,
      "branch_id": 2,
      "commission_id": 14,
      "commission_quantity": 32,
      "created_at": "2024-06-28T00:24:48.000000Z",
      "updated_at": "2024-06-28T00:24:48.000000Z",
      "product": {
        "id": 1,
        "name": "bale7",
        "type": "Bread",
        "unit_price": 7,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 2,
        "name": "tach_bet",
        "type": "sub",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 14,
        "product_id": 4,
        "discount_amount": 0.66,
        "commission_recipient_id": 3,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 2,
      "product_id": 3,
      "quantity": 1648,
      "adari": 33,
      "branch_id": 2,
      "commission_id": 3,
      "commission_quantity": 15,
      "created_at": "2024-05-30T12:18:38.000000Z",
      "updated_at": "2024-05-30T12:18:38.000000Z",
      "product": {
        "id": 3,
        "name": "Donut",
        "type": "Cake",
        "unit_price": 30,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 2,
        "name": "tach_bet",
        "type": "sub",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 3,
        "product_id": 2,
        "discount_amount": 0.85,
        "commission_recipient_id": 5,
        "status": "active",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 3,
      "product_id": 2,
      "quantity": 1736,
      "adari": 25,
      "branch_id": 1,
      "commission_id": 15,
      "commission_quantity": 44,
      "created_at": "2024-07-04T03:08:07.000000Z",
      "updated_at": "2024-07-04T03:08:07.000000Z",
      "product": {
        "id": 2,
        "name": "bale10",
        "type": "Bread",
        "unit_price": 10,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 1,
        "name": "saris",
        "type": "main",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 15,
        "product_id": 2,
        "discount_amount": 0.92,
        "commission_recipient_id": 4,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 4,
      "product_id": 2,
      "quantity": 4326,
      "adari": 36,
      "branch_id": 2,
      "commission_id": 17,
      "commission_quantity": 16,
      "created_at": "2024-07-12T21:30:41.000000Z",
      "updated_at": "2024-07-12T21:30:41.000000Z",
      "product": {
        "id": 2,
        "name": "bale10",
        "type": "Bread",
        "unit_price": 10,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 2,
        "name": "tach_bet",
        "type": "sub",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 17,
        "product_id": 3,
        "discount_amount": 0.81,
        "commission_recipient_id": 2,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 5,
      "product_id": 4,
      "quantity": 4516,
      "adari": 27,
      "branch_id": 1,
      "commission_id": 12,
      "commission_quantity": 10,
      "created_at": "2024-01-29T00:59:05.000000Z",
      "updated_at": "2024-01-29T00:59:05.000000Z",
      "product": {
        "id": 4,
        "name": "Kuribat",
        "type": "Cake",
        "unit_price": 40,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 1,
        "name": "saris",
        "type": "main",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 12,
        "product_id": 4,
        "discount_amount": 0.39,
        "commission_recipient_id": 3,
        "status": "active",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 6,
      "product_id": 2,
      "quantity": 4061,
      "adari": 17,
      "branch_id": 1,
      "commission_id": 9,
      "commission_quantity": 17,
      "created_at": "2024-03-20T13:33:42.000000Z",
      "updated_at": "2024-03-20T13:33:42.000000Z",
      "product": {
        "id": 2,
        "name": "bale10",
        "type": "Bread",
        "unit_price": 10,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 1,
        "name": "saris",
        "type": "main",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 9,
        "product_id": 2,
        "discount_amount": 0.48,
        "commission_recipient_id": 3,
        "status": "active",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 7,
      "product_id": 4,
      "quantity": 1732,
      "adari": 36,
      "branch_id": 3,
      "commission_id": 19,
      "commission_quantity": 43,
      "created_at": "2024-02-02T02:58:59.000000Z",
      "updated_at": "2024-02-02T02:58:59.000000Z",
      "product": {
        "id": 4,
        "name": "Kuribat",
        "type": "Cake",
        "unit_price": 40,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 3,
        "name": "worku sefer",
        "type": "sub",
        "created_at": "2024-07-19T11:07:56.000000Z",
        "updated_at": "2024-07-19T11:07:56.000000Z"
      },
      "commission": {
        "id": 19,
        "product_id": 1,
        "discount_amount": 0.9,
        "commission_recipient_id": 3,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 8,
      "product_id": 2,
      "quantity": 4124,
      "adari": 4,
      "branch_id": 2,
      "commission_id": 17,
      "commission_quantity": 47,
      "created_at": "2024-05-13T09:11:39.000000Z",
      "updated_at": "2024-05-13T09:11:39.000000Z",
      "product": {
        "id": 2,
        "name": "bale10",
        "type": "Bread",
        "unit_price": 10,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 2,
        "name": "tach_bet",
        "type": "sub",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 17,
        "product_id": 3,
        "discount_amount": 0.81,
        "commission_recipient_id": 2,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 9,
      "product_id": 2,
      "quantity": 1090,
      "adari": 25,
      "branch_id": 2,
      "commission_id": 14,
      "commission_quantity": 13,
      "created_at": "2024-05-03T02:27:13.000000Z",
      "updated_at": "2024-05-03T02:27:13.000000Z",
      "product": {
        "id": 2,
        "name": "bale10",
        "type": "Bread",
        "unit_price": 10,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 2,
        "name": "tach_bet",
        "type": "sub",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 14,
        "product_id": 4,
        "discount_amount": 0.66,
        "commission_recipient_id": 3,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 10,
      "product_id": 3,
      "quantity": 3426,
      "adari": 24,
      "branch_id": 1,
      "commission_id": 20,
      "commission_quantity": 3,
      "created_at": "2024-02-22T04:38:56.000000Z",
      "updated_at": "2024-02-22T04:38:56.000000Z",
      "product": {
        "id": 3,
        "name": "Donut",
        "type": "Cake",
        "unit_price": 30,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 1,
        "name": "saris",
        "type": "main",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 20,
        "product_id": 2,
        "discount_amount": 0.37,
        "commission_recipient_id": 4,
        "status": "active",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 11,
      "product_id": 3,
      "quantity": 4031,
      "adari": 25,
      "branch_id": 3,
      "commission_id": 3,
      "commission_quantity": 33,
      "created_at": "2024-06-20T02:31:29.000000Z",
      "updated_at": "2024-06-20T02:31:29.000000Z",
      "product": {
        "id": 3,
        "name": "Donut",
        "type": "Cake",
        "unit_price": 30,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 3,
        "name": "worku sefer",
        "type": "sub",
        "created_at": "2024-07-19T11:07:56.000000Z",
        "updated_at": "2024-07-19T11:07:56.000000Z"
      },
      "commission": {
        "id": 3,
        "product_id": 2,
        "discount_amount": 0.85,
        "commission_recipient_id": 5,
        "status": "active",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 12,
      "product_id": 3,
      "quantity": 862,
      "adari": 18,
      "branch_id": 2,
      "commission_id": 4,
      "commission_quantity": 21,
      "created_at": "2024-02-22T16:25:07.000000Z",
      "updated_at": "2024-02-22T16:25:07.000000Z",
      "product": {
        "id": 3,
        "name": "Donut",
        "type": "Cake",
        "unit_price": 30,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 2,
        "name": "tach_bet",
        "type": "sub",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 4,
        "product_id": 4,
        "discount_amount": 0.09,
        "commission_recipient_id": 1,
        "status": "active",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 13,
      "product_id": 3,
      "quantity": 4884,
      "adari": 29,
      "branch_id": 2,
      "commission_id": 8,
      "commission_quantity": 8,
      "created_at": "2024-02-16T19:43:07.000000Z",
      "updated_at": "2024-02-16T19:43:07.000000Z",
      "product": {
        "id": 3,
        "name": "Donut",
        "type": "Cake",
        "unit_price": 30,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 2,
        "name": "tach_bet",
        "type": "sub",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 8,
        "product_id": 2,
        "discount_amount": 0.41,
        "commission_recipient_id": 2,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 14,
      "product_id": 4,
      "quantity": 1974,
      "adari": 38,
      "branch_id": 1,
      "commission_id": 17,
      "commission_quantity": 10,
      "created_at": "2024-07-17T23:55:08.000000Z",
      "updated_at": "2024-07-17T23:55:08.000000Z",
      "product": {
        "id": 4,
        "name": "Kuribat",
        "type": "Cake",
        "unit_price": 40,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 1,
        "name": "saris",
        "type": "main",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 17,
        "product_id": 3,
        "discount_amount": 0.81,
        "commission_recipient_id": 2,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 15,
      "product_id": 3,
      "quantity": 4774,
      "adari": 26,
      "branch_id": 1,
      "commission_id": 20,
      "commission_quantity": 6,
      "created_at": "2024-01-22T18:43:24.000000Z",
      "updated_at": "2024-01-22T18:43:24.000000Z",
      "product": {
        "id": 3,
        "name": "Donut",
        "type": "Cake",
        "unit_price": 30,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 1,
        "name": "saris",
        "type": "main",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 20,
        "product_id": 2,
        "discount_amount": 0.37,
        "commission_recipient_id": 4,
        "status": "active",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 16,
      "product_id": 4,
      "quantity": 762,
      "adari": 38,
      "branch_id": 1,
      "commission_id": 15,
      "commission_quantity": 25,
      "created_at": "2024-04-20T04:56:44.000000Z",
      "updated_at": "2024-04-20T04:56:44.000000Z",
      "product": {
        "id": 4,
        "name": "Kuribat",
        "type": "Cake",
        "unit_price": 40,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 1,
        "name": "saris",
        "type": "main",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 15,
        "product_id": 2,
        "discount_amount": 0.92,
        "commission_recipient_id": 4,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 17,
      "product_id": 1,
      "quantity": 968,
      "adari": 26,
      "branch_id": 2,
      "commission_id": 5,
      "commission_quantity": 44,
      "created_at": "2024-04-07T04:05:26.000000Z",
      "updated_at": "2024-04-07T04:05:26.000000Z",
      "product": {
        "id": 1,
        "name": "bale7",
        "type": "Bread",
        "unit_price": 7,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 2,
        "name": "tach_bet",
        "type": "sub",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 5,
        "product_id": 2,
        "discount_amount": 0.41,
        "commission_recipient_id": 4,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 18,
      "product_id": 4,
      "quantity": 3339,
      "adari": 29,
      "branch_id": 1,
      "commission_id": 1,
      "commission_quantity": 48,
      "created_at": "2024-02-27T03:39:18.000000Z",
      "updated_at": "2024-02-27T03:39:18.000000Z",
      "product": {
        "id": 4,
        "name": "Kuribat",
        "type": "Cake",
        "unit_price": 40,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 1,
        "name": "saris",
        "type": "main",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 1,
        "product_id": 4,
        "discount_amount": 0.49,
        "commission_recipient_id": 4,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 19,
      "product_id": 4,
      "quantity": 2051,
      "adari": 33,
      "branch_id": 1,
      "commission_id": 19,
      "commission_quantity": 5,
      "created_at": "2024-06-15T16:05:10.000000Z",
      "updated_at": "2024-06-15T16:05:10.000000Z",
      "product": {
        "id": 4,
        "name": "Kuribat",
        "type": "Cake",
        "unit_price": 40,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 1,
        "name": "saris",
        "type": "main",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 19,
        "product_id": 1,
        "discount_amount": 0.9,
        "commission_recipient_id": 3,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 20,
      "product_id": 2,
      "quantity": 3652,
      "adari": 7,
      "branch_id": 2,
      "commission_id": 13,
      "commission_quantity": 37,
      "created_at": "2024-03-30T15:13:38.000000Z",
      "updated_at": "2024-03-30T15:13:38.000000Z",
      "product": {
        "id": 2,
        "name": "bale10",
        "type": "Bread",
        "unit_price": 10,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 2,
        "name": "tach_bet",
        "type": "sub",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 13,
        "product_id": 3,
        "discount_amount": 0.17,
        "commission_recipient_id": 1,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 21,
      "product_id": 2,
      "quantity": 2574,
      "adari": 4,
      "branch_id": 2,
      "commission_id": 12,
      "commission_quantity": 15,
      "created_at": "2024-02-18T07:59:04.000000Z",
      "updated_at": "2024-02-18T07:59:04.000000Z",
      "product": {
        "id": 2,
        "name": "bale10",
        "type": "Bread",
        "unit_price": 10,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 2,
        "name": "tach_bet",
        "type": "sub",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 12,
        "product_id": 4,
        "discount_amount": 0.39,
        "commission_recipient_id": 3,
        "status": "active",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 22,
      "product_id": 3,
      "quantity": 2948,
      "adari": 23,
      "branch_id": 2,
      "commission_id": 17,
      "commission_quantity": 14,
      "created_at": "2024-02-01T16:56:16.000000Z",
      "updated_at": "2024-02-01T16:56:16.000000Z",
      "product": {
        "id": 3,
        "name": "Donut",
        "type": "Cake",
        "unit_price": 30,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 2,
        "name": "tach_bet",
        "type": "sub",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 17,
        "product_id": 3,
        "discount_amount": 0.81,
        "commission_recipient_id": 2,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 23,
      "product_id": 4,
      "quantity": 1362,
      "adari": 9,
      "branch_id": 2,
      "commission_id": 20,
      "commission_quantity": 5,
      "created_at": "2024-03-29T08:42:06.000000Z",
      "updated_at": "2024-03-29T08:42:06.000000Z",
      "product": {
        "id": 4,
        "name": "Kuribat",
        "type": "Cake",
        "unit_price": 40,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 2,
        "name": "tach_bet",
        "type": "sub",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 20,
        "product_id": 2,
        "discount_amount": 0.37,
        "commission_recipient_id": 4,
        "status": "active",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 24,
      "product_id": 3,
      "quantity": 3281,
      "adari": 9,
      "branch_id": 2,
      "commission_id": 8,
      "commission_quantity": 9,
      "created_at": "2024-05-29T12:28:11.000000Z",
      "updated_at": "2024-05-29T12:28:11.000000Z",
      "product": {
        "id": 3,
        "name": "Donut",
        "type": "Cake",
        "unit_price": 30,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 2,
        "name": "tach_bet",
        "type": "sub",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 8,
        "product_id": 2,
        "discount_amount": 0.41,
        "commission_recipient_id": 2,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 25,
      "product_id": 4,
      "quantity": 2999,
      "adari": 8,
      "branch_id": 2,
      "commission_id": 19,
      "commission_quantity": 9,
      "created_at": "2024-03-15T18:53:59.000000Z",
      "updated_at": "2024-03-15T18:53:59.000000Z",
      "product": {
        "id": 4,
        "name": "Kuribat",
        "type": "Cake",
        "unit_price": 40,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 2,
        "name": "tach_bet",
        "type": "sub",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 19,
        "product_id": 1,
        "discount_amount": 0.9,
        "commission_recipient_id": 3,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 26,
      "product_id": 2,
      "quantity": 2979,
      "adari": 8,
      "branch_id": 1,
      "commission_id": 20,
      "commission_quantity": 40,
      "created_at": "2024-07-11T01:48:18.000000Z",
      "updated_at": "2024-07-11T01:48:18.000000Z",
      "product": {
        "id": 2,
        "name": "bale10",
        "type": "Bread",
        "unit_price": 10,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 1,
        "name": "saris",
        "type": "main",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 20,
        "product_id": 2,
        "discount_amount": 0.37,
        "commission_recipient_id": 4,
        "status": "active",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 27,
      "product_id": 2,
      "quantity": 3019,
      "adari": 35,
      "branch_id": 2,
      "commission_id": 3,
      "commission_quantity": 35,
      "created_at": "2024-01-25T07:36:06.000000Z",
      "updated_at": "2024-01-25T07:36:06.000000Z",
      "product": {
        "id": 2,
        "name": "bale10",
        "type": "Bread",
        "unit_price": 10,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 2,
        "name": "tach_bet",
        "type": "sub",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 3,
        "product_id": 2,
        "discount_amount": 0.85,
        "commission_recipient_id": 5,
        "status": "active",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 28,
      "product_id": 4,
      "quantity": 2081,
      "adari": 9,
      "branch_id": 1,
      "commission_id": 1,
      "commission_quantity": 6,
      "created_at": "2024-04-04T21:16:17.000000Z",
      "updated_at": "2024-04-04T21:16:17.000000Z",
      "product": {
        "id": 4,
        "name": "Kuribat",
        "type": "Cake",
        "unit_price": 40,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 1,
        "name": "saris",
        "type": "main",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 1,
        "product_id": 4,
        "discount_amount": 0.49,
        "commission_recipient_id": 4,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 29,
      "product_id": 1,
      "quantity": 2220,
      "adari": 14,
      "branch_id": 1,
      "commission_id": 19,
      "commission_quantity": 50,
      "created_at": "2024-05-02T11:35:48.000000Z",
      "updated_at": "2024-05-02T11:35:48.000000Z",
      "product": {
        "id": 1,
        "name": "bale7",
        "type": "Bread",
        "unit_price": 7,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 1,
        "name": "saris",
        "type": "main",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 19,
        "product_id": 1,
        "discount_amount": 0.9,
        "commission_recipient_id": 3,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 30,
      "product_id": 4,
      "quantity": 1053,
      "adari": 22,
      "branch_id": 2,
      "commission_id": 16,
      "commission_quantity": 38,
      "created_at": "2024-02-09T21:38:35.000000Z",
      "updated_at": "2024-02-09T21:38:35.000000Z",
      "product": {
        "id": 4,
        "name": "Kuribat",
        "type": "Cake",
        "unit_price": 40,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 2,
        "name": "tach_bet",
        "type": "sub",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 16,
        "product_id": 2,
        "discount_amount": 0.63,
        "commission_recipient_id": 5,
        "status": "active",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 31,
      "product_id": 2,
      "quantity": 1536,
      "adari": 7,
      "branch_id": 2,
      "commission_id": 10,
      "commission_quantity": 24,
      "created_at": "2024-02-25T05:07:59.000000Z",
      "updated_at": "2024-02-25T05:07:59.000000Z",
      "product": {
        "id": 2,
        "name": "bale10",
        "type": "Bread",
        "unit_price": 10,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 2,
        "name": "tach_bet",
        "type": "sub",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 10,
        "product_id": 3,
        "discount_amount": 0.89,
        "commission_recipient_id": 3,
        "status": "active",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 32,
      "product_id": 4,
      "quantity": 4575,
      "adari": 11,
      "branch_id": 1,
      "commission_id": 19,
      "commission_quantity": 45,
      "created_at": "2024-06-17T22:29:11.000000Z",
      "updated_at": "2024-06-17T22:29:11.000000Z",
      "product": {
        "id": 4,
        "name": "Kuribat",
        "type": "Cake",
        "unit_price": 40,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 1,
        "name": "saris",
        "type": "main",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 19,
        "product_id": 1,
        "discount_amount": 0.9,
        "commission_recipient_id": 3,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 33,
      "product_id": 1,
      "quantity": 2386,
      "adari": 19,
      "branch_id": 1,
      "commission_id": 9,
      "commission_quantity": 32,
      "created_at": "2024-06-19T09:45:46.000000Z",
      "updated_at": "2024-06-19T09:45:46.000000Z",
      "product": {
        "id": 1,
        "name": "bale7",
        "type": "Bread",
        "unit_price": 7,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 1,
        "name": "saris",
        "type": "main",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 9,
        "product_id": 2,
        "discount_amount": 0.48,
        "commission_recipient_id": 3,
        "status": "active",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 34,
      "product_id": 4,
      "quantity": 2649,
      "adari": 26,
      "branch_id": 2,
      "commission_id": 18,
      "commission_quantity": 49,
      "created_at": "2024-04-02T11:57:09.000000Z",
      "updated_at": "2024-04-02T11:57:09.000000Z",
      "product": {
        "id": 4,
        "name": "Kuribat",
        "type": "Cake",
        "unit_price": 40,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 2,
        "name": "tach_bet",
        "type": "sub",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 18,
        "product_id": 4,
        "discount_amount": 0.13,
        "commission_recipient_id": 5,
        "status": "active",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 35,
      "product_id": 3,
      "quantity": 1472,
      "adari": 3,
      "branch_id": 1,
      "commission_id": 3,
      "commission_quantity": 33,
      "created_at": "2024-04-16T05:29:16.000000Z",
      "updated_at": "2024-04-16T05:29:16.000000Z",
      "product": {
        "id": 3,
        "name": "Donut",
        "type": "Cake",
        "unit_price": 30,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 1,
        "name": "saris",
        "type": "main",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 3,
        "product_id": 2,
        "discount_amount": 0.85,
        "commission_recipient_id": 5,
        "status": "active",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 36,
      "product_id": 3,
      "quantity": 3318,
      "adari": 28,
      "branch_id": 1,
      "commission_id": 1,
      "commission_quantity": 6,
      "created_at": "2024-05-04T03:16:06.000000Z",
      "updated_at": "2024-05-04T03:16:06.000000Z",
      "product": {
        "id": 3,
        "name": "Donut",
        "type": "Cake",
        "unit_price": 30,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 1,
        "name": "saris",
        "type": "main",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 1,
        "product_id": 4,
        "discount_amount": 0.49,
        "commission_recipient_id": 4,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 37,
      "product_id": 3,
      "quantity": 233,
      "adari": 12,
      "branch_id": 1,
      "commission_id": 1,
      "commission_quantity": 9,
      "created_at": "2024-06-12T09:41:53.000000Z",
      "updated_at": "2024-06-12T09:41:53.000000Z",
      "product": {
        "id": 3,
        "name": "Donut",
        "type": "Cake",
        "unit_price": 30,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 1,
        "name": "saris",
        "type": "main",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 1,
        "product_id": 4,
        "discount_amount": 0.49,
        "commission_recipient_id": 4,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 38,
      "product_id": 3,
      "quantity": 4591,
      "adari": 0,
      "branch_id": 2,
      "commission_id": 6,
      "commission_quantity": 27,
      "created_at": "2024-05-09T20:55:53.000000Z",
      "updated_at": "2024-05-09T20:55:53.000000Z",
      "product": {
        "id": 3,
        "name": "Donut",
        "type": "Cake",
        "unit_price": 30,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 2,
        "name": "tach_bet",
        "type": "sub",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 6,
        "product_id": 4,
        "discount_amount": 0.29,
        "commission_recipient_id": 1,
        "status": "active",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 39,
      "product_id": 3,
      "quantity": 2872,
      "adari": 32,
      "branch_id": 1,
      "commission_id": 18,
      "commission_quantity": 0,
      "created_at": "2024-03-29T11:54:28.000000Z",
      "updated_at": "2024-03-29T11:54:28.000000Z",
      "product": {
        "id": 3,
        "name": "Donut",
        "type": "Cake",
        "unit_price": 30,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 1,
        "name": "saris",
        "type": "main",
        "created_at": "2024-07-19T08:58:42.000000Z",
        "updated_at": "2024-07-19T08:58:42.000000Z"
      },
      "commission": {
        "id": 18,
        "product_id": 4,
        "discount_amount": 0.13,
        "commission_recipient_id": 5,
        "status": "active",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    },
    {
      "id": 40,
      "product_id": 2,
      "quantity": 169,
      "adari": 40,
      "branch_id": 3,
      "commission_id": 8,
      "commission_quantity": 21,
      "created_at": "2024-03-25T22:17:12.000000Z",
      "updated_at": "2024-03-25T22:17:12.000000Z",
      "product": {
        "id": 2,
        "name": "bale10",
        "type": "Bread",
        "unit_price": 10,
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      },
      "branch": {
        "id": 3,
        "name": "worku sefer",
        "type": "sub",
        "created_at": "2024-07-19T11:07:56.000000Z",
        "updated_at": "2024-07-19T11:07:56.000000Z"
      },
      "commission": {
        "id": 8,
        "product_id": 2,
        "discount_amount": 0.41,
        "commission_recipient_id": 2,
        "status": "inactive",
        "created_at": "2024-07-19T10:05:24.000000Z",
        "updated_at": "2024-07-19T10:05:24.000000Z"
      }
    }
  ],
  "cost": [
    {
      "id": 1,
      "quantity": 19,
      "inventory_item_id": 38,
      "user_id": 5,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 1,
      "inventory_item": {
        "id": 38,
        "item_name": "corrupti",
        "brand_id": 14,
        "unit_id": 4,
        "quantity": 3280,
        "price": 1165,
        "total_price": 3821200,
        "user_id": 3,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 14
      },
      "unit": {
        "id": 1,
        "name": "kg",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 2,
      "quantity": 11,
      "inventory_item_id": 20,
      "user_id": 7,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 1,
      "inventory_item": {
        "id": 20,
        "item_name": "laborum",
        "brand_id": 8,
        "unit_id": 2,
        "quantity": 364,
        "price": 2153,
        "total_price": 783692,
        "user_id": 3,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 43
      },
      "unit": {
        "id": 1,
        "name": "kg",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 3,
      "quantity": 18,
      "inventory_item_id": 4,
      "user_id": 10,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 1,
      "inventory_item": {
        "id": 4,
        "item_name": "reiciendis",
        "brand_id": 2,
        "unit_id": 3,
        "quantity": 2547,
        "price": 2273,
        "total_price": 5789331,
        "user_id": 3,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 4
      },
      "unit": {
        "id": 1,
        "name": "kg",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 4,
      "quantity": 18,
      "inventory_item_id": 14,
      "user_id": 1,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 4,
      "inventory_item": {
        "id": 14,
        "item_name": "modi",
        "brand_id": 7,
        "unit_id": 3,
        "quantity": 2851,
        "price": 460,
        "total_price": 1311460,
        "user_id": 10,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 10
      },
      "unit": {
        "id": 4,
        "name": "pcs",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 5,
      "quantity": 16,
      "inventory_item_id": 34,
      "user_id": 10,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 4,
      "inventory_item": {
        "id": 34,
        "item_name": "et",
        "brand_id": 5,
        "unit_id": 1,
        "quantity": 2730,
        "price": 2398,
        "total_price": 6546540,
        "user_id": 3,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 30
      },
      "unit": {
        "id": 4,
        "name": "pcs",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 6,
      "quantity": 10,
      "inventory_item_id": 29,
      "user_id": 10,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 4,
      "inventory_item": {
        "id": 29,
        "item_name": "rerum",
        "brand_id": 10,
        "unit_id": 2,
        "quantity": 4808,
        "price": 868,
        "total_price": 4173344,
        "user_id": 11,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 39
      },
      "unit": {
        "id": 4,
        "name": "pcs",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 7,
      "quantity": 19,
      "inventory_item_id": 2,
      "user_id": 8,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 4,
      "inventory_item": {
        "id": 2,
        "item_name": "magnam",
        "brand_id": 12,
        "unit_id": 4,
        "quantity": 2463,
        "price": 590,
        "total_price": 1453170,
        "user_id": 4,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 28
      },
      "unit": {
        "id": 4,
        "name": "pcs",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 8,
      "quantity": 18,
      "inventory_item_id": 28,
      "user_id": 4,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 2,
      "inventory_item": {
        "id": 28,
        "item_name": "quia",
        "brand_id": 3,
        "unit_id": 2,
        "quantity": 2811,
        "price": 2067,
        "total_price": 5810337,
        "user_id": 8,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 45
      },
      "unit": {
        "id": 2,
        "name": "ltr",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 9,
      "quantity": 11,
      "inventory_item_id": 14,
      "user_id": 9,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 2,
      "inventory_item": {
        "id": 14,
        "item_name": "modi",
        "brand_id": 7,
        "unit_id": 3,
        "quantity": 2851,
        "price": 460,
        "total_price": 1311460,
        "user_id": 10,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 10
      },
      "unit": {
        "id": 2,
        "name": "ltr",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 10,
      "quantity": 16,
      "inventory_item_id": 19,
      "user_id": 2,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 2,
      "inventory_item": {
        "id": 19,
        "item_name": "enim",
        "brand_id": 1,
        "unit_id": 3,
        "quantity": 2410,
        "price": 225,
        "total_price": 542250,
        "user_id": 10,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 30
      },
      "unit": {
        "id": 2,
        "name": "ltr",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 11,
      "quantity": 20,
      "inventory_item_id": 12,
      "user_id": 2,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 2,
      "inventory_item": {
        "id": 12,
        "item_name": "est",
        "brand_id": 8,
        "unit_id": 3,
        "quantity": 3567,
        "price": 2433,
        "total_price": 8678511,
        "user_id": 2,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 46
      },
      "unit": {
        "id": 2,
        "name": "ltr",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 12,
      "quantity": 17,
      "inventory_item_id": 32,
      "user_id": 7,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 4,
      "inventory_item": {
        "id": 32,
        "item_name": "ut",
        "brand_id": 1,
        "unit_id": 4,
        "quantity": 2971,
        "price": 1754,
        "total_price": 5211134,
        "user_id": 9,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 16
      },
      "unit": {
        "id": 4,
        "name": "pcs",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 13,
      "quantity": 12,
      "inventory_item_id": 17,
      "user_id": 9,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 1,
      "inventory_item": {
        "id": 17,
        "item_name": "fugiat",
        "brand_id": 3,
        "unit_id": 4,
        "quantity": 3867,
        "price": 998,
        "total_price": 3859266,
        "user_id": 9,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 30
      },
      "unit": {
        "id": 1,
        "name": "kg",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 14,
      "quantity": 12,
      "inventory_item_id": 21,
      "user_id": 5,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 3,
      "inventory_item": {
        "id": 21,
        "item_name": "doloremque",
        "brand_id": 13,
        "unit_id": 1,
        "quantity": 4022,
        "price": 1254,
        "total_price": 5043588,
        "user_id": 11,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 50
      },
      "unit": {
        "id": 3,
        "name": "gm",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 15,
      "quantity": 18,
      "inventory_item_id": 27,
      "user_id": 1,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 2,
      "inventory_item": {
        "id": 27,
        "item_name": "blanditiis",
        "brand_id": 12,
        "unit_id": 3,
        "quantity": 734,
        "price": 2172,
        "total_price": 1594248,
        "user_id": 11,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 48
      },
      "unit": {
        "id": 2,
        "name": "ltr",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 16,
      "quantity": 10,
      "inventory_item_id": 30,
      "user_id": 4,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 4,
      "inventory_item": {
        "id": 30,
        "item_name": "aut",
        "brand_id": 4,
        "unit_id": 2,
        "quantity": 3850,
        "price": 1155,
        "total_price": 4446750,
        "user_id": 4,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 50
      },
      "unit": {
        "id": 4,
        "name": "pcs",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 17,
      "quantity": 17,
      "inventory_item_id": 3,
      "user_id": 6,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 3,
      "inventory_item": {
        "id": 3,
        "item_name": "numquam",
        "brand_id": 2,
        "unit_id": 2,
        "quantity": 2793,
        "price": 1070,
        "total_price": 2988510,
        "user_id": 11,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 36
      },
      "unit": {
        "id": 3,
        "name": "gm",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 18,
      "quantity": 20,
      "inventory_item_id": 2,
      "user_id": 1,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 4,
      "inventory_item": {
        "id": 2,
        "item_name": "magnam",
        "brand_id": 12,
        "unit_id": 4,
        "quantity": 2463,
        "price": 590,
        "total_price": 1453170,
        "user_id": 4,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 28
      },
      "unit": {
        "id": 4,
        "name": "pcs",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 19,
      "quantity": 17,
      "inventory_item_id": 29,
      "user_id": 10,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 3,
      "inventory_item": {
        "id": 29,
        "item_name": "rerum",
        "brand_id": 10,
        "unit_id": 2,
        "quantity": 4808,
        "price": 868,
        "total_price": 4173344,
        "user_id": 11,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 39
      },
      "unit": {
        "id": 3,
        "name": "gm",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 20,
      "quantity": 11,
      "inventory_item_id": 1,
      "user_id": 7,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 3,
      "inventory_item": {
        "id": 1,
        "item_name": "sint",
        "brand_id": 10,
        "unit_id": 2,
        "quantity": 707,
        "price": 588,
        "total_price": 415716,
        "user_id": 9,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 23
      },
      "unit": {
        "id": 3,
        "name": "gm",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 21,
      "quantity": 16,
      "inventory_item_id": 32,
      "user_id": 2,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 2,
      "inventory_item": {
        "id": 32,
        "item_name": "ut",
        "brand_id": 1,
        "unit_id": 4,
        "quantity": 2971,
        "price": 1754,
        "total_price": 5211134,
        "user_id": 9,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 16
      },
      "unit": {
        "id": 2,
        "name": "ltr",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 22,
      "quantity": 20,
      "inventory_item_id": 34,
      "user_id": 4,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 1,
      "inventory_item": {
        "id": 34,
        "item_name": "et",
        "brand_id": 5,
        "unit_id": 1,
        "quantity": 2730,
        "price": 2398,
        "total_price": 6546540,
        "user_id": 3,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 30
      },
      "unit": {
        "id": 1,
        "name": "kg",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 23,
      "quantity": 16,
      "inventory_item_id": 2,
      "user_id": 11,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 1,
      "inventory_item": {
        "id": 2,
        "item_name": "magnam",
        "brand_id": 12,
        "unit_id": 4,
        "quantity": 2463,
        "price": 590,
        "total_price": 1453170,
        "user_id": 4,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 28
      },
      "unit": {
        "id": 1,
        "name": "kg",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 24,
      "quantity": 10,
      "inventory_item_id": 37,
      "user_id": 3,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 2,
      "inventory_item": {
        "id": 37,
        "item_name": "exercitationem",
        "brand_id": 3,
        "unit_id": 4,
        "quantity": 3141,
        "price": 2237,
        "total_price": 7026417,
        "user_id": 8,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 28
      },
      "unit": {
        "id": 2,
        "name": "ltr",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 25,
      "quantity": 18,
      "inventory_item_id": 16,
      "user_id": 11,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 1,
      "inventory_item": {
        "id": 16,
        "item_name": "at",
        "brand_id": 11,
        "unit_id": 2,
        "quantity": 1016,
        "price": 151,
        "total_price": 153416,
        "user_id": 4,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 46
      },
      "unit": {
        "id": 1,
        "name": "kg",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 26,
      "quantity": 17,
      "inventory_item_id": 25,
      "user_id": 4,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 2,
      "inventory_item": {
        "id": 25,
        "item_name": "sit",
        "brand_id": 9,
        "unit_id": 1,
        "quantity": 4341,
        "price": 996,
        "total_price": 4323636,
        "user_id": 4,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 13
      },
      "unit": {
        "id": 2,
        "name": "ltr",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 27,
      "quantity": 15,
      "inventory_item_id": 30,
      "user_id": 6,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 1,
      "inventory_item": {
        "id": 30,
        "item_name": "aut",
        "brand_id": 4,
        "unit_id": 2,
        "quantity": 3850,
        "price": 1155,
        "total_price": 4446750,
        "user_id": 4,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 50
      },
      "unit": {
        "id": 1,
        "name": "kg",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 28,
      "quantity": 12,
      "inventory_item_id": 1,
      "user_id": 10,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 1,
      "inventory_item": {
        "id": 1,
        "item_name": "sint",
        "brand_id": 10,
        "unit_id": 2,
        "quantity": 707,
        "price": 588,
        "total_price": 415716,
        "user_id": 9,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 23
      },
      "unit": {
        "id": 1,
        "name": "kg",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 29,
      "quantity": 11,
      "inventory_item_id": 29,
      "user_id": 6,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 1,
      "inventory_item": {
        "id": 29,
        "item_name": "rerum",
        "brand_id": 10,
        "unit_id": 2,
        "quantity": 4808,
        "price": 868,
        "total_price": 4173344,
        "user_id": 11,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 39
      },
      "unit": {
        "id": 1,
        "name": "kg",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 30,
      "quantity": 12,
      "inventory_item_id": 36,
      "user_id": 4,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 2,
      "inventory_item": {
        "id": 36,
        "item_name": "recusandae",
        "brand_id": 13,
        "unit_id": 3,
        "quantity": 325,
        "price": 499,
        "total_price": 162175,
        "user_id": 9,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 37
      },
      "unit": {
        "id": 2,
        "name": "ltr",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 31,
      "quantity": 14,
      "inventory_item_id": 31,
      "user_id": 11,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 4,
      "inventory_item": {
        "id": 31,
        "item_name": "tempore",
        "brand_id": 13,
        "unit_id": 4,
        "quantity": 388,
        "price": 2484,
        "total_price": 963792,
        "user_id": 5,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 15
      },
      "unit": {
        "id": 4,
        "name": "pcs",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 32,
      "quantity": 18,
      "inventory_item_id": 8,
      "user_id": 9,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 4,
      "inventory_item": {
        "id": 8,
        "item_name": "id",
        "brand_id": 10,
        "unit_id": 1,
        "quantity": 3861,
        "price": 321,
        "total_price": 1239381,
        "user_id": 6,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 13
      },
      "unit": {
        "id": 4,
        "name": "pcs",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 33,
      "quantity": 15,
      "inventory_item_id": 4,
      "user_id": 11,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 3,
      "inventory_item": {
        "id": 4,
        "item_name": "reiciendis",
        "brand_id": 2,
        "unit_id": 3,
        "quantity": 2547,
        "price": 2273,
        "total_price": 5789331,
        "user_id": 3,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 4
      },
      "unit": {
        "id": 3,
        "name": "gm",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 34,
      "quantity": 18,
      "inventory_item_id": 34,
      "user_id": 2,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 3,
      "inventory_item": {
        "id": 34,
        "item_name": "et",
        "brand_id": 5,
        "unit_id": 1,
        "quantity": 2730,
        "price": 2398,
        "total_price": 6546540,
        "user_id": 3,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 30
      },
      "unit": {
        "id": 3,
        "name": "gm",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 35,
      "quantity": 17,
      "inventory_item_id": 33,
      "user_id": 7,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 2,
      "inventory_item": {
        "id": 33,
        "item_name": "deserunt",
        "brand_id": 8,
        "unit_id": 4,
        "quantity": 1157,
        "price": 316,
        "total_price": 365612,
        "user_id": 7,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 48
      },
      "unit": {
        "id": 2,
        "name": "ltr",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 36,
      "quantity": 16,
      "inventory_item_id": 36,
      "user_id": 2,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 4,
      "inventory_item": {
        "id": 36,
        "item_name": "recusandae",
        "brand_id": 13,
        "unit_id": 3,
        "quantity": 325,
        "price": 499,
        "total_price": 162175,
        "user_id": 9,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 37
      },
      "unit": {
        "id": 4,
        "name": "pcs",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 37,
      "quantity": 17,
      "inventory_item_id": 40,
      "user_id": 2,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:43.000000Z",
      "updated_at": "2024-07-16T14:32:43.000000Z",
      "unit_id": 3,
      "inventory_item": {
        "id": 40,
        "item_name": "minus",
        "brand_id": 6,
        "unit_id": 3,
        "quantity": 2266,
        "price": 783,
        "total_price": 1774278,
        "user_id": 2,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 46
      },
      "unit": {
        "id": 3,
        "name": "gm",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 38,
      "quantity": 18,
      "inventory_item_id": 9,
      "user_id": 8,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:44.000000Z",
      "updated_at": "2024-07-16T14:32:44.000000Z",
      "unit_id": 1,
      "inventory_item": {
        "id": 9,
        "item_name": "iusto",
        "brand_id": 11,
        "unit_id": 3,
        "quantity": 29,
        "price": 1667,
        "total_price": 48343,
        "user_id": 4,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 3
      },
      "unit": {
        "id": 1,
        "name": "kg",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 39,
      "quantity": 13,
      "inventory_item_id": 15,
      "user_id": 6,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:44.000000Z",
      "updated_at": "2024-07-16T14:32:44.000000Z",
      "unit_id": 1,
      "inventory_item": {
        "id": 15,
        "item_name": "velit",
        "brand_id": 9,
        "unit_id": 3,
        "quantity": 1989,
        "price": 1875,
        "total_price": 3729375,
        "user_id": 9,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 31
      },
      "unit": {
        "id": 1,
        "name": "kg",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    },
    {
      "id": 40,
      "quantity": 15,
      "inventory_item_id": 24,
      "user_id": 1,
      "receiver_user_id": null,
      "created_at": "2024-07-16T14:32:44.000000Z",
      "updated_at": "2024-07-16T14:32:44.000000Z",
      "unit_id": 2,
      "inventory_item": {
        "id": 24,
        "item_name": "quae",
        "brand_id": 10,
        "unit_id": 2,
        "quantity": 3011,
        "price": 1818,
        "total_price": 5473998,
        "user_id": 9,
        "created_at": "2024-07-16T14:32:42.000000Z",
        "updated_at": "2024-07-16T14:32:42.000000Z",
        "batch_number": 46
      },
      "unit": {
        "id": 2,
        "name": "ltr",
        "created_at": "2024-07-16T14:32:41.000000Z",
        "updated_at": "2024-07-16T14:32:41.000000Z"
      }
    }
  ]
}
