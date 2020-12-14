# Buto-Plugin-ValidateDouble
Plugin form/form_v1 double validator.

## Settings
```
item:
  weight:
    type: varchar
    label: Weight
    validator:
      -
        plugin: validate/double
        method: validate_double
        data:
          decimals: 2
```
