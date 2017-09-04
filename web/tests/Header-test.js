import React from 'react';
import { expect } from 'chai';
import { shallow, mount, render } from 'enzyme';
import commonHeader from  '../src/components/Header';

describe("<commonHeader />", function() {
  it("shallow", function() {
    expect(shallow(<commonHeader />).is('.ant-layout-header')).to.equal(false);
  });

  it("mount", function() {
    expect(mount(<commonHeader />).find('.ant-layout-header').length).to.not.equal(1);
  });
});
