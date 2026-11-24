import assert from 'node:assert/strict';
import { test } from 'node:test';
import { auditMigration } from '../migration/audit';

test('every tracked PHP source has a functional replacement or a documented nonfunctional classification', () => {
  const audit = auditMigration();
  assert.ok(audit.totalPhpFiles >= 200);
  assert.ok(audit.functionalFiles > 180);
  assert.deepEqual(audit.unmapped, []);
  assert.deepEqual(audit.missingTargets, []);
  assert.equal(audit.functionalFiles + audit.referenceOrNonfunctionalFiles, audit.totalPhpFiles);
  assert.ok(audit.files.find(file => file.source === 'php/app/manage.php')!.legacyEvents.length > 100);
});
